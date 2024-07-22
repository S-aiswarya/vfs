<?php

namespace App\Services;

use App\Models\TaskNote;
use App\Events\TimelineChanged;
use App\Http\Resources\Apis\TaskNoteResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskNoteService{

    public function store(array $inputData)
    {
        $obj = new TaskNote();
        $obj->fill($inputData);
        if($obj->save()){
            $this->createTimeline('task_note_created', $obj->task);
            return new TaskNoteResource($obj);
        }
        return null;
    }

    public function update(array $inputData)
    {
        $id = $inputData['id'];
        if($obj = TaskNote::find($id)){
            if($obj->update($inputData)){
                $this->createTimeline('task_note_updated', $obj->task);
                return new TaskNoteResource($obj);
            }
        }   
        return null;
    }

    public function delete(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = TaskNote::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $task = $item->task;
        if($item->delete())
        {
            $this->createTimeline('task_note_deleted', $task);
            return response()->json(['message' => 'Task note successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $task){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'task_note_created':
                $description = "A note has been created by {$user}";
                break;
            
            case 'task_note_updated':
                $description = "A note has been updated by {$user}";
                break;

            case 'task_note_deleted':
                $description = "A note has been deleted by {$user}";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Task::class, $task->id, request()->post());
    }
}