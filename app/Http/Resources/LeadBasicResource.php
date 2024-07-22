<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadBasicResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'phone_number' => $this->phone_number,
            'alternate_phone_number' => $this->alternate_phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'assignedToOffice' => new OfficeResource($this->assignedToOffice),
            'assignedTo' => new UserResource($this->assignedTo),
            'closed' => $this->closed,
        ];
    }
}
