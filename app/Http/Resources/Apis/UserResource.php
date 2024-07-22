<?php

namespace App\Http\Resources\Apis;

use App\Http\Resources\LeadBasicResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'office_country' => new CountryResource($this->officeCountry),
            'phone_number' => $this->phone_number,
            'role' => new RoleResource($this->role),
            'manager' => new UserResource($this->manger),
            'has_permission_to_access_unallocated_leads' => $this->has_permission_to_access_unallocated_leads,
            'lead' => new LeadBasicResource($this->lead)
        ];

        if(!empty($this->token))
            $data['_token'] = $this->token;

        return $data;
    }
}
