<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'city_id'=>$this->city_id,
            'address'=>$this->address,
            'created_by'=>$this->created_by,
            'lastUpdatedBy' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at

        ];
    }
}
