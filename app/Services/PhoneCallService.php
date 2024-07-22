<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\PhoneCallResource;
use App\Models\Application;
use App\Models\Lead;
use App\Models\PhoneCall;
use Illuminate\Http\Request;

class PhoneCallService{

    public function store(array $data)
    {
        $item = new PhoneCall();
        $data['date_time_of_call'] = date('Y-m-d H:i:s', strtotime($data['date_time_of_call']));
        $item->fill($data);
        if($item->save()){
            $this->createTimeline('phone_call_created', $item->lead, $item->application_id);
            return new PhoneCallResource($item);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        if($item = PhoneCall::find($id)){
            if($item->update($data)){
                $this->createTimeline('phone_call_updated', $item->lead, $item->application_id);
                return new PhoneCallResource($item);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function delete(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = PhoneCall::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $lead_to_delete = $item->lead;
        if($item->delete())
        {
            $this->createTimeline('phone_call_deleted', $lead_to_delete, $lead_to_delete->application_id);
            return response()->json(['message' => 'Phone call log successfully deleted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead, $application_id=null){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'phone_call_created':
                $description = "Phone call has been logged by {$user}";
                break;
            
            case 'phone_call_updated':
                $description = "Phone call log has been updated by {$user}";
                break;

            case 'phone_call_deleted':
                $description = "Phone call log has been deleted by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            if($application_id)
                TimelineChanged::dispatch($type, $description, Application::class, $application_id, request()->post());
        }
    }
}