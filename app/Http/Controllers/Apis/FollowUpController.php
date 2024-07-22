<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\FollowUpRequest;
use App\Http\Resources\FollowUpResource;
use App\Http\Resources\FollowUpResourceCollection;
use App\Models\FollowUp;
use App\Services\FollowUpService;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index(Request $request, $lead_id)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = FollowUp::where('lead_id', $lead_id)->where('type', 'Follow Up');

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
        }

        if(!empty($data['assigned_to'])){
            $items = $items->where('assigned_to', $data['assigned_to']);
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

    public function store(FollowUpRequest $request, FollowUpService $service)
    {
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id)
    {
        $item = FollowUp::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new FollowUpResource($item);
    }

    public function update(FollowUpRequest $request, FollowUpService $service)
    {
        $request->validated();
        return $service->update($request->all());
    }

    public function completed(Request $request, FollowUpService $service)
    {
        return $service->complete($request);
    }

    public function delete(Request $request, FollowUpService $service)
    {
        return $service->delete($request);
    }
}
