<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'location_id'=>$this->location_id,
            'token_prefix'=>$this->token_prefix,
            'created_by'=>$this->created_by,
            'lastUpdatedBy' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at

        ];
    }
}
