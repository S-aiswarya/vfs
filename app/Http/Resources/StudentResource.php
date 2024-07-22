<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'state' => $this->state,
            'lead_id' => $this->lead?->id,
            'intake' => new IntakeResource($this->intake),
            'country_of_birth' => new CountryListResource($this->countryOfBirth),
            'country_of_residence' => new CountryListResource($this->countryOfResidence),
            'applications' => new ApplicationResourceCollection($this->applications)
        ];
    }
}
