<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaymentResourceCollection;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Payment();

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
        }
        
        if(!empty($data['lead_id']))
            $items = $items->where('lead_id', $data['lead_id']);

        if(!empty($data['student_id']))
            $items = $items->where('student_id', $data['student_id']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by', $data['created_by']);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new PaymentResourceCollection($items);
    }

    public function view($id)
    {
        $item = Payment::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new PaymentResource($item);
    }

    public function store(PaymentRequest $request, PaymentService $service){
        $request->validated();
        return $service->store($request);
    }

    public function update(PaymentRequest $request, PaymentService $service){
        $request->validated();
        return $service->update($request);
    }
}
