<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskNoteRequest;
use App\Http\Resources\Apis\TaskNoteResource;
use App\Http\Resources\Apis\TaskNoteResourceCollection;
use App\Models\TaskNote;
use App\Services\TaskNoteService;
use Illuminate\Http\Request;

class TaskNoteController extends Controller
{
    public function index($task_id, Request $request)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = TaskNote::where('task_id', $task_id);

        if(!empty($data['keyword']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('note', 'LIKE', '%'.$data['keyword'].'%');
            });
        }

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new TaskNoteResourceCollection($items);
    }

    public function store(TaskNoteRequest $request, TaskNoteService $service){
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id){
        $item = TaskNote::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new TaskNoteResource($item);
    }

    public function update(TaskNoteRequest $request, TaskNoteService $service){
        $request->validated();
        return $service->update($request->all());
    }

    public function delete(Request $request, TaskNoteService $service){
        return $service->delete($request);
    }
}
