<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\Apis\TaskResource;
use App\Http\Resources\Apis\TaskResourceCollection;
use App\Http\Resources\TimelineResourceCollection;
use App\Models\ApiLog;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use DB;

class TaskController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Task();

        $items = $this->checkAccess($items);

        if(!empty($data['keyword']))
        {
            $items = $items->where(function($query) use($data){
                $query->where('title', 'LIKE', '%'.$data['keyword'].'%');
            });
        }

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
        }

        if(!empty($data['lead_id'])){
            $items = $items->where('lead_id', $data['lead_id']);
        }

        if(!empty($data['status'])){
            $items = $items->where('status', $data['status']);
        }

        if(!empty($data['priority'])){
            $items = $items->where('priority', $data['priority']);
        }

        if(!empty($data['assigned_to_user'])){
            $items = $items->where('assigned_to_user_id', $data['assigned_to_user']);
        }

        if(!empty($data['assigned_by_user'])){
            $items = $items->where('assigned_by_user_id', $data['assigned_by_user']);
        }

        if(!empty($data['reviewer'])){
            $items = $items->where('reviewer_id', $data['reviewer']);
        }

        if(!empty($data['created_by'])){
            $items = $items->where('created_by', $data['created_by']);
        }

        if(isset($data['from']) || isset($data['to']))
        {
            $from = (isset($data['from']) && trim($data['from']) != "")?date('Y-m-d', strtotime($data['from'])):date('Y').'-'.date('m').'-01';
            $to = (isset($data['to']) && trim($data['to']) != "")?date('Y-m-d', strtotime($data['to'])):date('Y-m-d');
            $items = $items->whereBetween('due_date', array($from, $to));
        }

        if(!empty($data['overdue'])){
            $items = $items->where('due_date', '<=', date('Y-m-d'));
        }

        if(!empty($data['archived']))
            $items = $items->where('archived', 1);
        else
            $items = $items->where('archived', 0);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new TaskResourceCollection($items);
    }

    protected function checkAccess($item){
        if(auth()->user()->tokenCan('role:manager')){
            $offices = $this->getAuthOffices();
            $office_users = $this->getAuthOfficeUsers($offices);


            $item = $item->where(function($query) use($office_users){
                $query->whereIn('created_by', $office_users)->orWhereIn('assigned_to_user_id', $office_users);
            });
        }
        elseif(auth()->user()->tokenCan('role:consellor') || auth()->user()->tokenCan('role:app-coordinator')){
            $item = $item->where(function($query){
                $query->where('created_by', auth()->user()->id)->orWhere('assigned_to_user_id', auth()->user()->id);
            });
        }

        return $item;
    }

    public function store(TaskRequest $request, TaskService $service){
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id){
        $item = Task::where('id', $id);
        $item = $this->checkAccess($item);
        $item = $item->first();

        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new TaskResource($item);
    }

    public function update(TaskRequest $request, TaskService $service){
        $request->validated();
        return $service->update($request->all());
    }

    public function delete(Request $request, TaskService $service){
        return $service->delete($request);
    }

    public function changeStatus(Request $request, TaskService $service){
        return $service->changeStatus($request);
    }

    public function archive(Request $request, TaskService $service){
        return $service->archive($request);
    }

    public function reopen(Request $request, TaskService $service){
        return $service->reopen($request);
    }

    public function timeline($id, Request $request)
    {
        $data = $request->all();

        $item = Task::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;

        $items = ApiLog::where('relatable_type', Task::class)->where('relatable_id', $item->id);

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
