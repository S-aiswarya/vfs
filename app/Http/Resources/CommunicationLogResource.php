<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\AgencyResource;
use App\Http\Resources\Apis\LeadResource;
use App\Http\Resources\Apis\UniversityResource;
use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationLogResource extends JsonResource
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
            'to' => $this->to,
            'subject' => $this->subject,
            'body' => $this->message,
            'cc' => $this->cc,
            'type' => $this->type,
            'from' => $this->from,
            'from_original' => $this->from_original,
            'message_date' => $this->message_date,
            'attachments' => new EmailAttachmentResourceCollection($this->attachments),
            'lead' => new LeadResource($this->lead),
            'university' => new UniversityResource($this->university),
            'agency' => new AgencyResource($this->agency),
            'created_by' => new UserResource($this->created_user),
            'last_updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
