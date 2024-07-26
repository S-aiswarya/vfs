<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\RegisterTypeResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterTypeGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->group_name,
            'register_types' => new RegisterTypeResourceCollection($this->register_types)
        ];
    }
}
