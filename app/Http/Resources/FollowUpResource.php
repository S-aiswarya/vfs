<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowUpResource extends JsonResource
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
            'type' => $this->type,
            'follow_up_date' => $this->follow_up_date,
            'assigned_to_user' => new UserResource($this->assignedToUser),
            'note' => $this->note,
            'status' => $this->status,
            'created_by' => new UserResource($this->created_user),
            'last_updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
