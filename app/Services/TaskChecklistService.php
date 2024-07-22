<?php

namespace App\Services;

use App\Models\TaskChecklist;
use App\Events\TimelineChanged;
use App\Http\Resources\Apis\TaskChecklistResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskChecklistService{

    public function store(array $inputData)
    {
        $obj = new TaskChecklist();
        $obj->fill($inputData);
        if($obj->save()){
            $this->createTimeline('task_checklist_created', $obj->task);
            return new TaskChecklistResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(array $inputData)
    {
        $id = $inputData['id'];
        if($obj = TaskChecklist::find($id)){
            if($obj->update($inputData)){
                $this->createTimeline('task_checklist_updated', $obj->task);
                return new TaskChecklistResource($obj);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function delete(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = TaskChecklist::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $task = $item->task;
        if($item->delete())
        {
            $this->createTimeline('task_checklist_deleted', $task);
            return response()->json(['message' => 'Task checklist successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function completed(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = TaskChecklist::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item->completed = 1;
        if($item->save())
        {
            $this->createTimeline('task_checklist_completed', $item->task);
            return response()->json(['message' => 'Task checklist successfully completed.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $task){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'task_checklist_created':
                $description = "Checklist has been created by {$user}";
                break;
            
            case 'task_checklist_updated':
                $description = "Checklist has been updated by {$user}";
                break;

            case 'task_checklist_deleted':
                $description = "Checklist has been deleted by {$user}";
                break;

            case 'task_checklist_completed':
                $description = "Checklist has been completed by {$user}";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Task::class, $task->id, request()->post());
    }
}