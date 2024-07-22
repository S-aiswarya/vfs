<?php

namespace App\Services;

use App\Enum\StageType;
use App\Events\TimelineChanged;
use App\Events\StudentCreated;
use App\Http\Resources\Apis\LeadResource;
use App\Models\Application;
use App\Models\Lead;
use App\Models\LeadStageHistory;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\BCIE;
use DB;
use Illuminate\Support\Facades\Auth;

class LeadService{

    use BCIE;

    public function store(array $lead_data)
    {
        $lead = new Lead();
        $lead_data['country_id'] = Auth::user()->office_country_id;
        $lead->fill($lead_data);
        if($lead->save()){
            $lead_stage = $this->getStageByActionType(StageType::LeadCreate);
            if($lead_stage)
                $this->updateStage($lead, $lead_stage);

            $lead = Lead::find($lead->id);
            $this->createTimeline('lead_created', $lead);
            return new LeadResource($lead);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(array $lead_data)
    {
        $id = $lead_data['id'];
        if($lead = Lead::find($id)){
            if(!empty($lead_data['applicant_data_submitted'])){
                if(!$lead->assign_to_user_id)
                    return response()->json(['message' => 'Invalid Request'], 400);
            }
            if($lead->update($lead_data)){
                if(!empty($lead_data['applicant_data_submitted'])){
                    if($lead->user_id){
                        $user = $this->saveUser($lead_data, $lead->user);
                        $this->createTimeline('lead_application_data_updated', $lead);
                    }
                    else{
                        $user = $this->saveUser($lead_data, new User());
                        if($user){
                            $lead->user_id = $user->id;
                            if($lead->save()){
                                $lead->plain_password = $user->plain_password;
                                $lead_stage = $this->getStageByActionType(StageType::LeadConfirmed);
                                if($lead_stage)
                                    $this->updateStage($lead, $lead_stage);
                            }
                            $this->createTimeline('lead_application_data_submitted', $lead);
                            if($user->plain_password)
                                StudentCreated::dispatch($lead);
                        }
                    }
                }
                $lead = Lead::find($lead->id);
                $this->createTimeline('lead_updated', $lead);
                return new LeadResource($lead);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    protected function saveUser($inputData, $userObj){
        if(!$userObj->id){
            $check_exist = User::where(function($query) use($inputData){
                $query->where('email', $inputData['email'])->orWhere('phone_number', $inputData['phone_number']);
            })->where('status', 1)->first();
            if(!$check_exist){
                $userObj->user_type = 'traveller';
                $userObj->email = $inputData['email'];
                $userObj->phone_number = $inputData['phone_number'];
                $password = $this->generateRandomPassword(6);
                $userObj->password = Hash::make($password);
                $userObj->created_by = auth()->user()->id;
            }
            else
                return $check_exist;
        }
        $userObj->updated_by = auth()->user()->id;
        $userObj->name = $inputData['name'];
        $userObj->save();
        if(!empty($password))
            $userObj->plain_password = $password;
        return $userObj;
    }

    protected function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function delete(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Lead::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $lead_to_delete = $item;
        if($item->delete())
        {
            $this->createTimeline('lead_deleted', $lead_to_delete);
            return response()->json(['message' => 'Lead successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function restore(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Lead::withTrashed()->find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        if($item->restore())
        {
            $this->createTimeline('lead_restored', $item);
            return response()->json(['message' => 'Lead successfully restored.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function changeStage(Request $request){
        $id = $request->lead_id;
        if($lead = Lead::find($id)){
            return $this->updateStage($lead, $request->stage_id);
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function updateStage($lead, $new_stage){
        DB::beginTransaction();
        try {
            $old_stage = $lead->stage;
            $lead->stage_id = $new_stage;
            $lead->save();
            if($old_stage && ($old_stage->id != $lead->stage_id)){
                $lead->old_stage = $old_stage;
                $this->createTimeline('stage_changed', $lead);
                $stage_serivce = new StageService();
                $stage_serivce->taskAction($lead->stage_id, $lead);
                $stage_serivce->emailAction($lead->stage_id, $lead);
            }
            $this->saveStageHistory($lead);
            unset($lead->old_stage);
            DB::commit();
            return response()->json(['message' => 'Lead stage successfully changed.']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function close(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Lead::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->closed = 1;
        $item->archive_note = ($request->archive_note)?$request->archive_note:NULL;
        $item->archive_reason = ($request->archive_reason)?$request->archive_reason:NULL;
        if($item->save())
        {
            $this->createTimeline('lead_closed', $item);
            return response()->json(['message' => 'Lead successfully closed.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function reopen(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Lead::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->closed = 0;
        if($item->save())
        {
            $this->createTimeline('lead_reopened', $item);
            return response()->json(['message' => 'Lead successfully re-opened.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function withraw(Request $request)
    {
        DB::beginTransaction();
        try {
            if(!$request->id)
                return response()->json(['message' => 'Invalid Request'], 400);

            $item = Lead::find($request->id);
            if(!$item)
                return response()->json(['message' => 'Invalid Request'], 400);
            $item->withdrawn = 1;
            $item->withdrawn_on = date('Y-m-d H:i:s');
            $item->withdraw_reason = ($request->withdraw_reason)?$request->withdraw_reason:NULL;
            $item->save();

            Application::where('lead_id', $item->id)->update(['withdrawn'=>1]);
            $this->createTimeline('lead_withrawn', $item);
            
            DB::commit();
            return response()->json(['message' => "Lead has been successfully withrawn."]);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function withrawResume(Request $request)
    {
        DB::beginTransaction();
        try {
            if(!$request->id)
                return response()->json(['message' => 'Invalid Request'], 400);

            $item = Lead::find($request->id);
            if(!$item)
                return response()->json(['message' => 'Invalid Request'], 400);
            $item->withdrawn = 0;
            $item->withdrawn_resumed_on = date('Y-m-d H:i:s');
            $item->save();

            Application::where('lead_id', $item->id)->update(['withdrawn'=>0]);
            $this->createTimeline('lead_withrawn_resume', $item);
            
            DB::commit();
            return response()->json(['message' => "Lead has been successfully resumed."]);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function saveStageHistory($lead, $note=null){
        $lead_history = new LeadStageHistory();
        $lead_history->lead_id = $lead->id;
        $lead_history->stage_id = $lead->stage_id;
        $lead_history->note = $note;
        $lead_history->created_by = auth()->user()->id;
        $lead_history->save();
    }

    public function bulkAssign(Request $request){
        $leads = $request->leads;
        foreach($leads as $lead){
            $lead_obj = Lead::find($lead);
            if($lead_obj){
                $lead_obj->assign_to_office_id = $request->assign_to_office_id;
                $lead_obj->assign_to_user_id = $request->user_id;
                if($lead_obj->save()){
                    $this->createTimeline('lead_assigned', $lead_obj);
                }
            }
        }
        return response()->json(['message' => 'Leads have been successfully assigned.']);
    }

    public function roundRobinAssign(Request $request){
        $leads = $request->leads;
        $users = $request->users;

        $key = 0;
        foreach($leads as $lead){
            $lead_obj = Lead::find($lead);
            if($lead_obj){
                $current_user = $users[$key];
                $lead_obj->assign_to_office_id = $request->assign_to_office_id;
                $lead_obj->assign_to_user_id = $current_user;
                if($lead_obj->save()){
                    $this->createTimeline('lead_assigned', $lead_obj);
                }
                
                if(count($users) == $key+1)
                    $key = 0;
                else
                    $key++;
            }
        }
        return response()->json(['message' => 'Leads have been successfully assigned.']);
    }

    public function publicStore(array $lead_data)
    {
        $lead = new Lead();
        $lead->fill($lead_data);
        if($lead->save()){
            return new LeadResource($lead);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function basicStore($data){
        $lead = new Lead();
        $lead->name = !empty($data['name'])?$data['name']:$data['email'];
        $lead->email = !empty($data['email'])?(int)$data['email']:NULL;
        $lead->phone_number = !empty($data['phone_number'])?(int)$data['phone_number']:NULL;
        if($lead->save()){
            return $lead->id;
        }
        else
            return null;
    }

    public function changeCounselor($lead, $new_counselor){
        $old_counselor = $lead->assignedToCounsellor;

        $lead->assign_to_user_id = $new_counselor->id;
        if($lead->save()){
            $lead->old_counselor = $old_counselor;
            $this->createTimeline('lead_counselor_changed', $lead);
        }
    }

    public function createTimeline($type, $lead){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'lead_created':
                $description = "Lead has been created by {$user}";
                break;
            
            case 'lead_updated':
                $description = "Lead has been updated by {$user}";
                break;

            case 'lead_deleted':
                $description = "Lead has been deleted by {$user}";
                break;

            case 'lead_closed':
                $description = "Lead has been closed by {$user}";
                break;

            case 'lead_reopened':
                $description = "Lead has been re-opened by {$user}";
                break;

            case 'lead_application_data_submitted':
                $description = "Application data has been saved by {$user}";
                break;

            case 'lead_application_data_updated':
                $description = "Application data has been updated by {$user}";
                break;

            case 'lead_assigned':
                $description = "Lead has been assigned to {$lead->assignedTo->name} by {$user}";
                break;

            case 'lead_followup_assigned':
                $description = "Lead follow up has been assigned to {$lead->followUpAssignedToUser->name} by {$user}";
                break;

            case 'stage_changed':
                $description = "{$user} changed lead stage from {$lead->old_stage->name} to {$lead->stage->name}";
                break;

            case 'lead_counselor_changed':
                $description = "Lead counselor has been changed from {$lead->old_counselor->name} to {$lead->assignedToCounsellor->name}";
                break;

            case 'lead_deferred':
                $description = "Lead intake has been deferred from {$lead->old_intake->month} {$lead->old_intake->year} to {$lead->new_intake->month} {$lead->new_intake->year}";
                break;

            case 'lead_restored':
                $description = "Lead has been restored by {$user}";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
    }
}