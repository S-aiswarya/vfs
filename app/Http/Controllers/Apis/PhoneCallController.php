<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneCallRequest;
use App\Http\Resources\PhoneCallResource;
use App\Http\Resources\PhoneCallResourceCollection;
use App\Models\PhoneCall;
use App\Services\PhoneCallService;
use Illuminate\Http\Request;

class PhoneCallController extends Controller
{
    public function index(Request $request, $lead_id)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = PhoneCall::where('lead_id', $lead_id);

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
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
        return new PhoneCallResourceCollection($items);
    }

    public function store(PhoneCallRequest $request, PhoneCallService $service)
    {
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id)
    {
        $item = PhoneCall::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new PhoneCallResource($item);
    }

    public function update(PhoneCallRequest $request, PhoneCallService $service)
    {
        $request->validated();
        return $service->update($request->all());
    }

    public function delete(Request $request, PhoneCallService $service)
    {
        return $service->delete($request);
    }

    public function summary(Request $request){
        $data = $request->all();
        $call_log = new PhoneCall();

        if(!empty($data['lead_id']))
            $call_log = $call_log->where('lead_id', $data['lead_id']);

        if(!empty($data['application_id']))
            $call_log = $call_log->where('application_id', $data['application_id']);
        $calls_inbound = clone $call_log;
        $calls_outbound = clone $call_log;

        $output = [];

        $output['calls_inbound'] = $calls_inbound->where('type', 'Inbound')->count();
        $output['calls_outbound'] = $calls_outbound->where('type', 'Outbound')->count();
        return response()->json(['data' => $output]);
    }
}
