<?php

namespace App\Http\Resources\Apis;

use App\Http\Resources\ApplicationResourceCollection;
use App\Http\Resources\IntakeResource;
use App\Http\Resources\LeadNoteResource;
use App\Http\Resources\LeadSourceResource;
use App\Http\Resources\LeadStageHistoryResourceCollection;
use App\Http\Resources\OfficeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'phone_number' => $this->phone_number,
            'alternate_phone_number' => $this->alternate_phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'preferred_destinations' => $this->preferred_destinations,
            'preferred_packages' => $this->preferred_packages,
            'referrance_from' => $this->referrance_from,
            'stage' => new StageResource($this->stage),
            'stage_history' => new LeadStageHistoryResourceCollection($this->stageHistory),
            'lead_source' => new LeadSourceResource($this->leadSource),
            'agency' => new AgencyResource($this->agency),
            'assignedToOffice' => new OfficeResource($this->assignedToOffice),
            'assignedTo' => new UserResource($this->assignedTo),
            'verification_status' => $this->verification_status,
            'note' => $this->note,
            'closed' => $this->closed,
            'archive_note' => $this->archive_note,
            'archive_reason' => $this->archive_reason,
            'user' => new UserResource($this->user),
            'createdBy' => new UserResource($this?->created_user),
            'lastUpdatedBy' => new UserResource($this?->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'intake' => new IntakeResource($this->intake),
            'applications' => new ApplicationResourceCollection($this->applications)
        ];

        if($this->latest_task){
            $data['latest_task'] = new TaskResource($this->latest_task);
        }
        if($this->latest_note){
            $data['latest_lead_note'] = new LeadNoteResource($this->latest_note);
        }

        return $data;
    }
}
