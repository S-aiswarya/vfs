<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
         'id' => $this->id,
         'name' => $this->email,
         'phonenumber'=>$this->phonenumber,
         'lastUpdatedBy' => $this->updated_by,
         'entry_time' => $this->entry_time,
         'exit_time' => $this->exit_time,
         'created_at' => $this->created_at,
         'updated_at' => $this->updated_at,
         'deleted_at' => $this->deleted_at,
        ];
    }
}
