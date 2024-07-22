<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
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
            'subject' => $this->subject,
            'body' => $this->body,
            'body_footer' => $this->body_footer,
            'default_cc' => $this->default_cc,
            'attchments' => new EmailTemplateAttachmentResourceCollection($this->attachments),
            'created_by_user' => new UserResource($this->createdByUser),
            'updated_by_user' => new UserResource($this->updatedByUser),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        if($this->template)
            $data['template'] = $this->template;

        return $data;
    }
}
