<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TimelineChanged;
use App\Http\Resources\Apis\TaskResource;
use App\Models\Application;
use App\Models\Lead;
use Illuminate\Http\Request;

class TaskService{

    public function store(array $inputData)
    {
        $obj = new Task();
        $inputData = $this->processData($inputData);
        $obj->fill($inputData);
        if($obj->save()){
            $this->createTimeline('task_created', $obj);
            if($obj->lead_id)
                $this->createLeadTimeline('task_created', $obj);
            if($obj->assigned_to_user_id){
                $this->createTimeline('task_assigned', $obj);
                if($obj->application_id)
                    $this->createLeadTimeline('task_assigned', $obj, $obj->application_id);
            }
            return new TaskResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }



    public function update(array $inputData)
    {
        $inputData = $this->processData($inputData);
        $id = $inputData['id'];
        if($obj = Task::find($id)){

            if($obj->update($inputData)){
                      $this->createTimeline('task_updated', $obj);
                      
                if($obj->lead_id)
                    $this->createLeadTimeline('task_updated', $obj, $obj->application_id);

                if(!empty($inputData['assigned_to_user_id']) && $obj->assigned_to_user_id != $inputData['assigned_to_user_id']){
                    $this->createTimeline('task_assigned', $obj);

                    if($obj->lead_id)
                        $this->createLeadTimeline('task_assigned', $obj, $obj->application_id);
                }
                return new TaskResource($obj);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }




    public function delete(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Task::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item_to_delete = $item;
        if($item->delete())
        {
            $this->createTimeline('task_deleted', $item_to_delete);
            if($item_to_delete->lead_id)
                $this->createLeadTimeline('task_updated', $item_to_delete, $item_to_delete->application_id);
            return response()->json(['message' => 'Task successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function changeStatus(Request $request){
        if(!$request->id || !$request->status)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Task::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $old_status = $item->status;
        $item->status = $request->status;
        $item->status_note = ($request->status_note)?$request->status_note:NULL;
        if($item->save())
        {
            $item->old_status = $old_status;
            $this->createTimeline('task_status_changed', $item);
            if($item->lead_id)
                $this->createLeadTimeline('task_status_changed', $item, $item->application_id);
            return response()->json(['message' => 'Task status successfully updated.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function archive(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Task::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->archived = 1;
        if($item->save())
        {
            $this->createTimeline('task_archived', $item);
            if($item->lead_id)
                $this->createLeadTimeline('task_archived', $item, $item->application_id);
            return response()->json(['message' => 'Task successfully archived.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function reopen(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Task::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->archived = 0;
        if($item->save())
        {
            $this->createTimeline('task_reopened', $item);
            if($item->lead_id)
                $this->createLeadTimeline('task_reopened', $item, $item->application_id);
            return response()->json(['message' => 'Task successfully reopened.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function processData(array $data){
        $data['start_date'] = (!empty($data['start_date']))?date('Y-m-d H:i:s', strtotime($data['start_date'])):NULL;
        $data['end_date'] = (!empty($data['end_date']))?date('Y-m-d H:i:s', strtotime($data['end_date'])):NULL;
        $data['due_date'] = (!empty($data['due_date']))?date('Y-m-d H:i:s', strtotime($data['due_date'])):NULL;
        if(!empty($data['assigned_to_user_id']))
            $data['assigned_by_user_id'] = auth()->user()->id;
        return $data;
    }

    public function storeTaskAction($task_action, $assign_to, $lead_id, $application_id){
        $due_date = null;
        if($task_action->duration){
            $due_date = new \DateTime();
            $due_date->modify('+'.$task_action->duration.' minutes');
        }

        $task = new Task();
        $task->lead_id = $lead_id;
        $task->title = $task_action->title;
        $task->application_id = $application_id;
        $task->description = $task_action->description;
        $task->due_date = $due_date;
        $task->assigned_to_user_id = $assign_to;
        $task->assigned_by_user_id = auth()->user()->id;
        if($task->save()){
            if($task->lead_id)
                $this->createLeadTimeline('task_created', $task);
            if($task->assigned_to_user_id){
                $this->createTimeline('task_assigned', $task);
            }
        }
    }

    protected function createTimeline($type, $task){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'task_created':
                $description = "Task has been created by {$user}";
                break;

            case 'task_assigned':
                $description = "Task has been assigned to {$task->assigned_to_user->name} by {$user}";
                break;
            
            case 'task_updated':
                $description = "Task has been updated by {$user}";
                break;

            case 'task_deleted':
                $description = "Task has been deleted by {$user}";
                break;

            case 'task_status_changed':
                $description = "Status of the task has been changed from {$task->old_staus} to {$task->status} by {$user}";
                break;

            case 'task_archived':
                $description = "Task has been archived by {$user}";
                break;

            case 'task_reopened':
                $description = "Task has been reopened by {$user}";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Task::class, $task->id, request()->post());
    }

    protected function createLeadTimeline($type, $task, $application_id=null){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'task_created':
                $description = "Task has been created by {$user}";
                break;

            case 'task_assigned':
                $description = "Task has been assigned to {$task->assigned_to_user->name} by {$user}";
                break;
            
            case 'task_updated':
                $description = "Task has been updated by {$user}";
                break;

            case 'task_deleted':
                $description = "Task has been deleted by {$user}";
                break;

            case 'task_status_changed':
                $description = "Status of the task has been changed from {$task->old_staus} to {$task->status} by {$user}";
                break;

            case 'task_reopened':
                $description = "Task has been reopened by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $task->lead_id, request()->post());
            if($application_id)
                TimelineChanged::dispatch($type, $description, Application::class, $application_id, request()->post());
        }
    }
}