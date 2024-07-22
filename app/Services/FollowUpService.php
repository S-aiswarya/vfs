<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\FollowUpResource;
use App\Models\Application;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;

class FollowUpService{

    public function store(array $data)
    {
        $item = new FollowUp();
        $data['type'] = "Follow Up";
        $item->fill($data);
        if($item->save()){
            $this->createTimeline('follow_up_created', $item->lead, $item->application_id);
            return new FollowUpResource($item);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        if($item = FollowUp::find($id)){
            if($item->update($data)){
                $this->createTimeline('follow_up_updated', $item->lead, $item->application_id);
                return new FollowUpResource($item);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function delete(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = FollowUp::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $lead_to_delete = $item->lead;
        if($item->delete())
        {
            $this->createTimeline('follow_up_deleted', $lead_to_delete, $lead_to_delete->application_id);
            return response()->json(['message' => 'Follow up successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function complete(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = FollowUp::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->status = "Completed";
        if($item->save())
        {
            $this->createTimeline('follow_up_completed', $item->lead, $item->application_id);
            return response()->json(['message' => 'Follow up successfully completed.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead, $application_id=null){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'follow_up_created':
                $description = "Follow up has been created by {$user}";
                break;
            
            case 'follow_up_updated':
                $description = "Follow up has been updated by {$user}";
                break;

            case 'follow_up_completed':
                $description = "Follow up completed by {$user}";
                break;

            case 'follow_up_deleted':
                $description = "Follow up deleted by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            if($application_id)
                TimelineChanged::dispatch($type, $description, Application::class, $application_id, request()->post());
        }
    }
}