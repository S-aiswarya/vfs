<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadBulkAssignRequest;
use App\Http\Requests\LeadImportRequest;
use App\Http\Requests\LeadPublicRequest;
use App\Http\Requests\LeadRequest;
use App\Http\Requests\LeadRoundRobinAssignRequest;
use App\Http\Requests\LeadStageChangeRequest;
use App\Http\Resources\Apis\LeadResource;
use App\Http\Resources\Apis\LeadResourceCollection;
use App\Http\Resources\FollowUpResourceCollection;
use App\Http\Resources\TimelineResourceCollection;
use App\Imports\LeadImport;
use App\Models\ApiLog;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadWebhookData;
use App\Models\Task;
use App\Services\LeadService;
use Illuminate\Http\Request;
use DB;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Lead();

        $items = $this->checkAccess($items);

        if(!empty($data['keyword']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('name', 'LIKE', '%'.$data['keyword'].'%')
                        ->where('email', 'LIKE', '%'.$data['keyword'].'%')
                        ->where('phone_number', 'LIKE', '%'.$data['keyword'].'%');
            });
        }

        if(!empty($data['name'])){
            $items = $items->where('name', 'LIKE', '%'.$data['name'].'%');
        }

        if(!empty($data['email'])){
            $items = $items->where('email', $data['email']);
        }

        if(!empty($data['phone_number'])){
            $items = $items->where('phone_number', $data['phone_number']);
        }

        if(!empty($data['lead_id'])){
            $items = $items->where('id', $data['lead_id']);
        }

        if(!empty($data['stage'])){
            $items = $items->where('stage_id', $data['stage']);
        }

        if(!empty($data['agency'])){
            $items = $items->where('agency_id', $data['agency']);
        }

        if(!empty($data['assigned_to'])){
            $items = $items->where('assign_to_user_id', $data['assigned_to']);
        }


        if(!empty($data['created_by'])){
            $items = $items->where('created_by', $data['created_by']);
        }

        if(!empty($data['assign_to_office_id'])){
            $items = $items->where('assign_to_office_id', $data['assign_to_office_id']);
        }

        if(isset($data['from']) || isset($data['to']))
        {
            $from = (isset($data['from']) && trim($data['from']) != "")?date('Y-m-d', strtotime($data['from'])):date('Y').'-'.date('m').'-01';
            $to = (isset($data['to']) && trim($data['to']) != "")?date('Y-m-d', strtotime($data['to'])):date('Y-m-d');
            $items = $items->whereBetween(DB::raw('DATE(created_at)'), array($from, $to));
        }

        if(!empty($data['deleted'])){
            $items = $items->onlyTrashed();
        }

        if(!empty($data['closed'])){
            $items = $items->where('closed', 1);
        }
        else{
            $items = $items->where('closed', 0);
        }

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new LeadResourceCollection($items);
    }

    public function store(LeadRequest $request, LeadService $service)
    {
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id)
    {
        $item = Lead::where('id', $id);
        $item = $this->checkAccess($item);
        $item = $item->first();
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item->latest_task = $this->latestTask($item->id);
        $item->latest_note = $this->latestNote($item->id);
        return new LeadResource($item);
    }

    protected function checkAccess($item){
        if(auth()->user()->tokenCan('role:manager')){
            $offices = $this->getAuthOffices();
            $office_users = $this->getAuthOfficeUsers($offices);

            $item = $item->where(function($query) use($office_users, $offices){
                $query->whereIn('created_by', $office_users)->orWhereIn('assign_to_office_id', $offices);
            });
        }
        elseif(auth()->user()->tokenCan('role:sales')){
            $item = $item->where('assign_to_user_id', auth()->user()->id);
        }

        return $item;
    }

    protected function latestTask($lead_id){
        $task = Task::where('assigned_to_user_id', auth()->user()->id)->where(function($query){
            $today = date('Y-m-d');
            $query->where('due_date', '<=', $today)->orWhere('due_date', '>', $today);
        })->where('status', '!=', 'Completed')->where('lead_id', $lead_id)->orderBy('created_at', 'ASC')->first();
        return $task;
    }

    protected function latestNote($lead_id){
        $note = FollowUp::where('created_by', auth()->user()->id)->where('type', 'Note')->where('lead_id', $lead_id)->orderBy('created_at', 'DESC')->first();
        return $note;
    }

    public function update(LeadRequest $request, LeadService $service)
    {
        $request->validated();
        return $service->update($request->all());
    }

    public function delete(Request $request, LeadService $service)
    {
        return $service->delete($request);
    }

    public function restore(Request $request, LeadService $service)
    {
        return $service->restore($request);
    }

    public function timeline($id, Request $request)
    {
        $data = $request->all();

        $item = Lead::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = ApiLog::where('relatable_type', Lead::class)->where('relatable_id', $item->id);

        if(!empty($data['type'])){
            $items = $items->where('type', $data['type']);
        }

        if(!empty($data['created_by'])){
            $items = $items->where('created_by', $data['created_by']);
        }

        $order_field = 'created_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);

        return new TimelineResourceCollection($items);
        
    }

    public function changeStage(LeadStageChangeRequest $request, LeadService $service){
        $request->validated();
        return $service->changeStage($request);
    }

    public function close(Request $request, LeadService $service){
        return $service->close($request);
    }

    public function reopen(Request $request, LeadService $service){
        return $service->reopen($request);
    }

    public function bulkAssign(LeadBulkAssignRequest $request, LeadService $service){
        $request->validated();
        return $service->bulkAssign($request);
    }

    public function notesFollowUps($id, Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = FollowUp::where('lead_id', $id);

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
        }

        if(!empty($data['assigned_to'])){
            $items = $items->where('assign_to_user_id', $data['assigned_to']);
        }

        if(!empty($data['created_by'])){
            $items = $items->where('created_by', $data['created_by']);
        }

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new FollowUpResourceCollection($items);
    }

    public function roundRobinAssign(LeadRoundRobinAssignRequest $request, LeadService $service){
        $request->validated();
        return $service->roundRobinAssign($request);
    }

    public function publicStore(LeadPublicRequest $request, LeadService $service){
        $request->validated();
        return $service->publicStore($request->all());
    }

    public function import(LeadImportRequest $request){
        $request->validated();

        $file = $request->file('file');
        \Excel::import(new LeadImport, $file);
        
        return response()->json(['message' => 'Lead successfully imported.']);
    }

    public function  webHookSave(Request $request) {
        $data = $request->all();

        if(empty($data['passcode']) || $data['passcode'] != "Uy3E-7QQ6He9" || empty($data['DataFields']))
            return response()->json(['message' => 'Invalid Request'], 400);
        
        $details = json_encode($data);
        
        $webhook_data = new LeadWebhookData();
        $webhook_data->data =  $details;
        $webhook_data->save();
        
        $data = $this->parse($webhook_data->data);
        
        try {
            
            $lead = new Lead;
            
            $temp_data = $data;
            
            unset(
                $temp_data['crm_status'],
                $temp_data['crm_lead_type_id'],
                $temp_data['source_url'],
                $temp_data['email'],
                $temp_data['project_id'],
                $temp_data['name'],
                $temp_data['phone_number']
                );
                
            $data['extra_data'] = json_encode($temp_data);
            
            $data = $this->leadExistCheck($data);
            
            
            $lead->fill($data);
            
            if($lead->save()){
                
                $hook_obj = WebhookData::find($new->id);
                
                $hook_obj->lead_id = $lead->id;
                
                $hook_obj->parse_status = "Parse Success";
                
                $hook_obj->save();
                
                
                if($lead->email_already_exist_lead_id != NULL){
                    $email_lead_log = new DuplicateLeadLog;
                    $email_lead_log->lead_id = $lead->id;
                    $email_lead_log->email = $lead->email;
                    $email_lead_log->type = 'email';
                    $email_lead_log->save();
                }
                if($lead->phone_already_exist_lead_id != NULL){
                    $phone_lead_log = new DuplicateLeadLog;
                    $phone_lead_log->lead_id = $lead->id;
                    $phone_lead_log->phone_number = $lead->phone_number;
                    $phone_lead_log->type = 'phone';
                    $phone_lead_log->save();
                }
                
            }
            
            if(isset($data['source']) && $data['source'] == 'Facebook')
                $data['campaign_type_id'] = 1;
            
            if(isset($data['campaign_name']) && $data['campaign_name'] != null){
                
            $check_count = CampaignList::where('name',$data['campaign_name'])
                                        ->count();
                if($check_count == 0){
                    $new_campaign = new CampaignList;
                    $new_campaign->name = $data['campaign_name'];
                    if(isset($data['campaign_type_id'])){
                    $new_campaign->campaign_type_id = $data['campaign_type_id'];
                    }
                    $new_campaign->is_auto_added = 1;
                    $new_campaign->save(); 
                }    
            }
             
             
                $ProjectAssignedUser = ProjectAssignedUser::where('projects_id',$lead->project->id)->get();

                $users = $ProjectAssignedUser->map(function ($data) {
        
                    return [
        
                        "id"=>$data->user->id,
        
                        "name"=>$data->user->name,
                        
                        "email"=>$data->user->email,
        
                        "user_role"=>$data->user_role
                        
                    ];
        
                });
                
             $email_to =[
             'tony@spiderworks.in'
             ];
             
             foreach ($users as $key => $user) {
                 
                 
                 if($user['user_role'] == "manager" && !in_array($user['email'],$email_to))
                    $email_to[] = $user['email'];
                    
                    
            }
            
             $cc_temp = $email_to;
             
             $mail = new MailSettings;
             
            if(count($cc_temp)>1){
                
             unset($cc_temp[0]);

                          $mail = new MailSettings;
                          $mail->to($email_to[0])->cc($cc_temp)->send(new \App\Mail\LeadApiContactMail($lead)); 
             
            }else{
                
               $mail->to($email_to[0])->send(new \App\Mail\LeadApiContactMail($lead)); 
               
            }
            
             return response([

             'data' =>$lead,
    
             'status'=>true
    
            ]);
        }
        
        catch(Exception $e) {
            
            $hook_obj = WebhookData::find($new->id);
            
            $hook_obj->parse_status = "Parse Failed:".$e->getMessage();
            
            $hook_obj->save();
            
            echo 'Message: ' .$e->getMessage();
            
        }
        
    }
    
    protected function parse($data){

        $data = json_decode($data);
        
        $field_data = explode('||', $data['DataFields']);
        $field_data_array = [];

        foreach($field_data as $field){
            $field_array = explode(':', $field);
            $field_data_array[$field_array[0]] = $field_array[1];
        }

        return $field_data_array;
        
    }
}
