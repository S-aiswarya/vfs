<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheckInRequest;
use App\Http\Requests\Auth\CheckinUpdateRequest;
use App\Http\Resources\Apis\CheckinResource;
use App\Http\Resources\Apis\CheckinResourceCollection;
use App\Services\VisitorLogService;
use App\Models\CheckIn;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use DB;

class VisitorLogController extends Controller
{
    public function Checkin(CheckInRequest $request, VisitorLogService $service){
        $request->validated();
        return $service->store($request->all());
    }

     
        public function CheckinView($id){
            $item = CheckIn::where('id', $id);
            $item = $item->first();
            if(!$item)
                return response()->json(['message' => 'Invalid Request'], 400);
            return new CheckinResource($item);
        }
     


    
    public function Checkout(Request $request, VisitorLogService $service){
        if(!$request->id)

           return response()->json(['message' => 'Invalid Request'], 400);
        $item= CheckIn::find($request->id);


        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        else
            return $service->update($item, $request->note);
    }

    
   

      
    public function VisitorlogUpdate(CheckinUpdateRequest $request, VisitorLogService $service){
        $request->validated();
        $item=CheckIn::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        else
           return $service->Visitorlog($item, $request->all());   
    }

       


    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new CheckIn();

       
        if(!empty($data['name']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('name', 'LIKE', '%'.$data['name'].'%');
            });
        }

        if(!empty($data['email'])){
            $items = $items->where('email', $data['email']);
        }

        if(!empty($data['phonenumber'])){
            $items = $items->where('phonenumber', $data['phonenumber']);
        }

        if(!empty($data['token'])){
            $items = $items->where('token', $data['token']);
        }
         
        if(!empty($data['location_id'])){
            $items = $items->where('location_id', $data['location_id']);
        }

        if(!empty($data['center_id'])){
            $items = $items->where('center_id', $data['center_id']);
        }

        if(!empty($data['gate_id'])){
            $items = $items->where('gate_id', $data['gate_id']);
        }


        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new CheckinResourceCollection($items);
}
}