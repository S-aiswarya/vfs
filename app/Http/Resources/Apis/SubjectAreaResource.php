<?php

namespace App\Http\Resources\Apis;

use App\Http\Resources\CourseLevelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectAreaResource extends JsonResource
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
            'image' => ($this->image)?\App\Helpers\BladeHelper::asset($this->image):null,
            'course_level' => new CourseLevelResource($this->course_level)
        ];
    }
}
