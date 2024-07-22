<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ApplicationRequest;
use App\Http\Requests\Applications\Status\AppliedInUniversityRequest;
use App\Http\Requests\Applications\Status\CasApprovedRequest;
use App\Http\Requests\Applications\Status\UniversityAcceptedRequest;
use App\Http\Requests\Applications\Status\UniversityFeePaidRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationResourceCollection;
use App\Http\Resources\TimelineResourceCollection;
use App\Models\ApiLog;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Application();

        if(!empty($data['keyword']) || !empty($data['name']) || !empty($data['email']) || !empty($data['phone_number']))
        {
            $items = $items->whereHas('student', function($query) use($data){
                $query->whereHas('user', function($user) use($data){
                    $user->where('users.status', 1)
                        ->where(function($query2) use($data){
                            if(!empty($data['keyword'])){
                                $query2->where('name', 'LIKE', '%'.$data['keyword'].'%')
                                    ->orWhere('email', 'LIKE', '%'.$data['keyword'].'%')
                                    ->orWhere('phone_number', 'LIKE', '%'.$data['keyword'].'%');
                            }
                            if(!empty($data['name'])){
                                $query2->where('name', 'LIKE', '%'.$data['keyword'].'%');
                            }
                            if(!empty($data['email'])){
                                $query2->where('email', $data['email']);
                            }
                            if(!empty($data['phone_number'])){
                                $query2->where('phone_number', $data['phone_number']);
                            }
                        });
                });
            });
        }

        if(!empty($data['student_id']))
            $items = $items->where('student_id', $data['student_id']);

        if(!empty($data['country_id']))
            $items = $items->where('country_id', $data['country_id']);

        if(!empty($data['university_id']))
            $items = $items->where('university_id', $data['university_id']);

        if(!empty($data['intake_id']))
            $items = $items->where('intake_id', $data['intake_id']);

        if(!empty($data['subject_area_id']))
            $items = $items->where('subject_area_id', $data['subject_area_id']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by', $data['created_by']);

        if(!empty($data['status']))
            $items = $items->where('status', $data['status']);
        else
            $items = $items->where('status', '!=', "Admission Completed");

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
        $item = Application::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new ApplicationResource($item);
    }

    public function store(ApplicationRequest $request, ApplicationService $service){
        $request->validated();
        return $service->store($request);
    }

    public function update(ApplicationRequest $request, ApplicationService $service){
        $request->validated();
        return $service->update($request);
    }

    public function statusAppliedInUniversity(AppliedInUniversityRequest $request, ApplicationService $service){
        $request->validated();
        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusAppliedInUniversity($request, $item);
    }

    public function statusUniversityRejected(Request $request, ApplicationService $service){
        if(!$request->id)
        return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusUniversityRejected($request, $item);
    }

    public function statusUniversityAccepted(UniversityAcceptedRequest $request, ApplicationService $service){
        $request->validated();

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusUniversityAccepted($request, $item);
    }

    public function statusCasApproved(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusCasApproved($request, $item);
    }

    public function statusCasRejected(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusCasRejected($request, $item);
    }

    public function statusVisaApplied(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusVisaApplied($request, $item);
    }

    public function statusVisaApproved(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusVisaApproved($request, $item);
    }

    public function statusVisaRejected(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusVisaRejected($request, $item);
    }

    public function statusUniversityFeePaid(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusUniversityFeePaid($request, $item);
    }

    public function statusAdmissionCompleted(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->statusAdmissionCompleted($request, $item);
    }

    public function uploadAcceptanceLetter(UniversityAcceptedRequest $request, ApplicationService $service){
        $request->validated();

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->uploadAcceptanceLetter($request, $item);
    }

    public function uploadCasDocument(CasApprovedRequest $request, ApplicationService $service){
        $request->validated();

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->uploadCasDocument($request, $item);
    }

    public function uploadFeeReceipt(UniversityFeePaidRequest $request, ApplicationService $service){
        $request->validated();

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->uploadFeeReceipt($request, $item);
    }

    public function deleteAcceptanceLetter(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->deleteAcceptanceLetter($request, $item);
    }

    public function deleteCasDocument(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->deleteCasDocument($request, $item);
    }

    public function deleteFeeReceipt(Request $request, ApplicationService $service){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Application::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        return $service->deleteFeeReceipt($request, $item);
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
}
