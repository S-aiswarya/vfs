<?php

namespace App\Http\Resources\Apis;

use App\Http\Resources\UniversityContactResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UniversityResource extends JsonResource
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
            'name' => $this->name,
            'country' => new CountryResource($this->country),
            'logo' => ($this->logo)?\App\Helpers\BladeHelper::asset($this->logo):null,
            'extra_university_info' => $this->extra_university_info,
            'extra_scholarship_info' => $this->extra_scholarship_info,
            'portal_link' => $this->portal_link,
            'portal_username' => $this->portal_username,
            'portal_password' => $this->portal_password,
            'contacts' => new UniversityContactResourceCollection($this->contacts)
        ];
    }
}
