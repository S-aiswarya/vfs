<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventResourceCollection;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Event();

        if(!empty($data['keyword']))
            $items = $items->where('name', 'LIKE', '%'.$data['keyword'].'%');

        if(!empty($data['created_by']))
            $items = $items->where('created_by_user', $data['created_by']);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new EventResourceCollection($items);
    }

    public function view($id)
    {
        $item = Event::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new EventResource($item);
    }

    public function store(EventRequest $request, EventService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return new EventResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function update(EventRequest $request, EventService $service)
    {
        $request->validated();
        if($obj = $service->update($request, $request->id)){
            return new EventResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function form($token)
    {
        $item = Event::where('token', $token)->where('status', 1)->first();
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new EventResource($item);
    }

}
