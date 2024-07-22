<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskChecklistResource extends JsonResource
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
            'checklist' => $this->checklist,
            'completed' => $this->completed,
            'createdBy' => new UserResource($this->created_user),
            'lastUpdatedBy' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
