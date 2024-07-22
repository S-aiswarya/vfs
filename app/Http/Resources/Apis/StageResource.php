<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'description' => $this->description,
            'colour' => $this->colour,
            'action_type' => $this->action_type,
            //'parent' => new StageResource($this->parent),
            'sub_stages' => new StageResourceCollection($this->sub_stages)
        ];
    }
}
