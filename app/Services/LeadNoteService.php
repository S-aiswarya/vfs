<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\LeadNoteResource;
use App\Models\Application;
use App\Models\Lead;
use App\Models\FollowUp as LeadNote;
use Illuminate\Http\Request;

class LeadNoteService{

    public function store(array $data)
    {
        $item = new LeadNote();
        $data['type'] = "Note";
        $item->fill($data);
        if($item->save()){
            $this->createTimeline('lead_note_created', $item->lead, $item->application_id);
            return new LeadNoteResource($item);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        if($item = LeadNote::find($id)){
            if($item->update($data)){
                $this->createTimeline('lead_note_updated', $item->lead, $item->application_id);
                return new LeadNoteResource($item);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function delete(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = LeadNote::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $lead_to_delete = $item->lead;
        if($item->delete())
        {
            $this->createTimeline('lead_note_deleted', $lead_to_delete, $lead_to_delete->application_id);
            return response()->json(['message' => 'Note successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead, $application_id=null){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'lead_note_created':
                $description = "Note has been created by {$user}";
                break;
            
            case 'lead_note_updated':
                $description = "Note has been updated by {$user}";
                break;

            case 'lead_note_deleted':
                $description = "Note deleted by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            if($application_id)
                TimelineChanged::dispatch($type, $description, Application::class, $application_id, request()->post());
        }
    }
}