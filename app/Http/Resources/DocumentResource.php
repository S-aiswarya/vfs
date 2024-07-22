<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'document_template' => new DocumentTemplateResource($this->documentTemplate),
            'file_path' => $this->file,
            'file' => ($this->file)?\App\Helpers\BladeHelper::asset($this->file):null,
            'title' => $this->title,
            'note' => $this->note,
            'status' => $this->status,
            'application' => new ApplicationBaseResource($this->application),
            'uploaded_by' => new UserResource($this->uploadedUser),
            'created_by' => new UserResource($this->created_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
