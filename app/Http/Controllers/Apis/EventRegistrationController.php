<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRegistrationRequest;
use App\Http\Resources\EventRegistrationResource;
use App\Http\Resources\EventRegistrationResourceCollection;
use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        if(empty($data['event_id']))
            return response()->json(['message' => 'Invalid Request'], 400);

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = EventRegistration::where('event_id', $data['event_id']);

        if(!empty($data['keyword'])){
            $keyword = $data['keyword'];
            $items->where(function($query) use($keyword){
                $query->where('name', 'LIKE', '%'.$keyword.'%')->orWhere('email', 'LIKE', '%'.$keyword.'%')->orWhere('phone_number', 'LIKE', '%'.$keyword.'%');
            });
        }
        
        $order_field = 'created_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new EventRegistrationResourceCollection($items);
    }

    public function view($id)
    {
        $item = EventRegistration::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new EventRegistrationResource($item);
    }

    public function store(EventRegistrationRequest $request, EventRegistrationService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return new EventRegistrationResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

}
