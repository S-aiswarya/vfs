<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Apis\UserResource;

class UniversityDocumentResource extends JsonResource
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
            'document' => ($this->document)?\App\Helpers\BladeHelper::asset($this->document):null,
            'created_by' => new UserResource($this->created_user),
            'updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
