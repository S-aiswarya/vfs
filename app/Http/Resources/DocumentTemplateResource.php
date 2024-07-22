<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\StageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTemplateResource extends JsonResource
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
            'max_upload_size' => $this->max_upload_size,
            'stage' => new StageResource($this->stage)
        ];
    }
}
