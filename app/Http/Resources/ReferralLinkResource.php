<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\AgencyResource;
use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralLinkResource extends JsonResource
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
            'token' => $this->token,
            'title' => $this->title,
            'lead_source' => new LeadSourceResource($this->leadSource),
            'agency' => new AgencyResource($this->agency),
            'event' => new EventResource($this->event),
            'last_date_of_validity' => $this->last_date_of_validity,
            'top_description' => $this->top_description,
            'bottom_description' => $this->bottom_description,
            'private_remarks' => $this->private_remarks,
            'banner_image' => ($this->banner_image)?\App\Helpers\BladeHelper::asset($this->banner_image):null,
            'uploaded_by' => new UserResource($this->uploadedUser),
            'created_by' => new UserResource($this->created_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
