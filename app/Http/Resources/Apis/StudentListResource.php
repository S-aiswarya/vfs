<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentListResource extends JsonResource
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
            'title' => $this->user->title,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'alternate_phone_number' => $this->alternate_phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'address' => $this->address,
            'zipcode' => $this->zipcode,
            'state' => $this->state
        ];
    }
}
