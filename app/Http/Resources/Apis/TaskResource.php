<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'due_date' => $this->due_date,
            'assignedToUser' => new UserResource($this->assigned_to_user),
            'assignedByUser' => new UserResource($this->assigned_by_user),
            'reviewer' => new UserResource($this->reviewer),
            'priority' => $this->priority,
            'status' => $this->status,
            'status_note' => $this->status_note,
            'archived' => $this->archived,
            'createdBy' => new UserResource($this->created_user),
            'lastUpdatedBy' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'notes' => new TaskNoteResourceCollection($this->whenLoaded('notes')),
            'checklists' => new TaskChecklistResourceCollection($this->whenLoaded('checklists')),
        ];
    }
}
