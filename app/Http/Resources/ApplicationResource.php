<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\StageResource;
use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'lead' => new LeadBasicResource($this->lead),
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'citizenship_country_id' => new CountryListResource($this->citizenshipCountry),
            'address' => $this->address,
            'passport_number' => $this->passport_number,
            'passport_exp_date' => $this->passport_exp_date,
            'remarks' => $this->remarks,
            'stage' => new StageResource($this->stage),
            'stage_note' => $this->stage_note,
            'application_number' => $this->application_number,
            'documents' => new DocumentResourceCollection($this->documents),
            'created_by' => new UserResource($this->created_user),
            'updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
