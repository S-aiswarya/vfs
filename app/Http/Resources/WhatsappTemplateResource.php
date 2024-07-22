<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WhatsappTemplateResource extends JsonResource
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
            'template_name' => $this->template_name,
            'title' => $this->title,
            'content' => $this->content,
            'approved' => $this->approved,
            'created_by' => new UserResource($this->created_user),
            'updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        if($this->template)
            $data['template'] = $this->template;

        return $data;
    }
}
