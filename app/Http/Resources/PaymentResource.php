<?php

namespace App\Http\Resources;

use App\Http\Resources\Apis\LeadResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Apis\UserResource;

class PaymentResource extends JsonResource
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
            'lead' => new LeadResource($this->lead),
            'student' => new StudentResource($this->student),
            'amount' => $this->amount,
            'payment_mode' => $this->payment_mode,
            "payment_date" => $this->payment_date,
            'details' => $this->details,
            'receipt_file' => ($this->receipt_file)?\App\Helpers\BladeHelper::asset($this->receipt_file):null,
            'created_by' => new UserResource($this->created_user),
            'updated_by' => new UserResource($this->updated_user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
