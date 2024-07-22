<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskChecklistRequest;
use App\Http\Resources\Apis\TaskChecklistResource;
use App\Http\Resources\Apis\TaskChecklistResourceCollection;
use App\Models\TaskChecklist;
use App\Services\TaskChecklistService;
use Illuminate\Http\Request;

class TaskChecklistController extends Controller
{
    public function index($task_id, Request $request)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = TaskChecklist::where('task_id', $task_id);

        if(!empty($data['keyword']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('checklist', 'LIKE', '%'.$data['keyword'].'%');
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
        return new TaskChecklistResourceCollection($items);
    }

    public function store(TaskChecklistRequest $request, TaskChecklistService $service){
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id){
        $item = TaskChecklist::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new TaskChecklistResource($item);
    }

    public function update(TaskChecklistRequest $request, TaskChecklistService $service){
        $request->validated();
        return $service->update($request->all());
    }

    public function delete(Request $request, TaskChecklistService $service){
        return $service->delete($request);
    }

    public function completed(Request $request, TaskChecklistService $service){
        return $service->completed($request);
    }
}
