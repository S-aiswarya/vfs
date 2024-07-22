<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ApplicationRequest;
use App\Http\Requests\ApplicationStatusRequest;
use App\Http\Requests\ApplicationUniversityDocumentRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationResourceCollection;
use App\Http\Resources\TimelineResourceCollection;
use App\Models\ApiLog;
use App\Models\Application;
use App\Models\Stage;
use App\Models\ApplicationUniversityDocument;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use App\Traits\BCIE;

class ApplicationController extends Controller
{
    use BCIE;
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = Application::whereHas('lead');

        $items = $this->checkAccess($items);

        if(!empty($data['keyword']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('name', 'LIKE', '%'.$data['keyword'].'%')
                        ->where('email', 'LIKE', '%'.$data['keyword'].'%')
                        ->where('phone_number', 'LIKE', '%'.$data['keyword'].'%');
            });
        }

        if(!empty($data['phone_number']))
            $items = $items->where('phone_number', $data['phone_number']);

        if(!empty($data['email']))
            $items = $items->where('email', $data['email']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by', $data['created_by']);

        if(!empty($data['application_number']))
            $items = $items->where('application_number', $data['application_number']);

        if(!empty($data['assign_to_office_id']) || !empty($data['assign_to_user_id'])){
            $items = $items->whereHas('lead', function($lead) use($data){
            
                if(!empty($data['assign_to_office_id']))
                    $lead->where('assign_to_office_id', $data['assign_to_office_id']);

                if(!empty($data['assign_to_user_id']))
                    $lead->where('assign_to_user_id', $data['assign_to_user_id']);
            });
        }

        if(!empty($data['stage_id']))
            $items = $items->where('stage_id', $data['stage_id']);


        if(!empty($data['lead_id']))
            $items = $items->where('lead_id', $data['lead_id']);


        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new ApplicationResourceCollection($items);
    }

    public function view($id)
    {
        $item = Application::where('id', $id);
        $item = $this->checkAccess($item);
        $item = $item->first();
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new ApplicationResource($item);
    }

    protected function checkAccess($item){

        if(auth()->user()->tokenCan('role:sales')){
            $item = $item->whereHas('lead', function($query){
                $query->where('leads.assign_to_user_id', auth()->user()->id);
            });
        }
        elseif(auth()->user()->tokenCan('role:manager')){
            $offices = $this->getAuthOffices();
            $item = $item->whereHas('lead', function($query) use($offices){
                $query->whereIn('leads.assign_to_office_id', $offices);
            });
        }
        return $item;
    }

    public function store(ApplicationRequest $request, ApplicationService $service){
        $request->validated();
        return $service->store($request);
    }

    public function update(ApplicationRequest $request, ApplicationService $service){
        $request->validated();
        return $service->update($request);
    }

    public function changeStage(ApplicationStatusRequest $request, ApplicationService $service){
        $request->validated();
        $stage = Stage::where('type', 'Visa')->where('status', 1)->where('id', $request->stage)->first();
        if(!$stage)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        if($message = $service->changeStage($request, $item)){
            return response()->json(['message' => $message]);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function uploadUniversityDocument(ApplicationUniversityDocumentRequest $request, ApplicationService $service){
        $request->validated();

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->uploadUniversityDocument($request, $item);
    }

    public function deleteUniversityDocument(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = ApplicationUniversityDocument::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->deleteUniversityDocument($request, $item);
    }

    public function timeline($id, Request $request)
    {
        $data = $request->all();

        $item = Application::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = ApiLog::where('relatable_type', Application::class)->where('relatable_id', $item->id);

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

    public function saveUniversityId(Request $request, ApplicationService $service){
        if(!$request->id || !$request->university_id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
    
        return $service->saveUniversityId($item, $request->university_id);
    }
}
