<?php

namespace App\Http\Resources\Apis;

use App\Http\Resources\CheckinTypeResource;
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
         'name' => $this->name,
         'email' => $this->email,
         'phonenumber'=>$this->phonenumber,
         'token'=>$this->token,
         'check_in_type_id'=>$this->check_in_type_id,
         'checkin'=>new CheckinTypeResource($this->register_types),
         'lastUpdatedBy' => $this->updated_by,
         'entry_time' => $this->entry_time,
         'exit_time' => $this->exit_time,
         'created_at' => $this->created_at,
         'updated_at' => $this->updated_at,
         'deleted_at' => $this->deleted_at,
         'date' =>$this->date,
         'consignor' =>$this->consignor,
         'courier_agency' =>$this->courier_agency,
         'awb_no' =>$this->awb_no,
         'dated' =>$this->dated,
         'zip_lock_no' =>$this->zip_lock_no,
         'time_of_receipt' =>$this->time_of_receipt,
         'shipment_handed_over_to' =>$this->shipment_handed_over_to,
         'mobile_phone_unit_model' =>$this->mobile_phone_unit_model,
         'time_deposited' =>$this->time_deposited,
         'signature' =>$this->signature,
         'incident' =>$this->incident,
         'time_deposited' =>$this->time_deposited,
         'guard' =>$this->guard,
         'key_box' =>$this->key_box,
         'key_number' =>$this->key_number,
         'name_of_staff' =>$this->name_of_staff,
         'key_box_opening_time' =>$this->key_box_opening_time,
         'key_box_closing_time' =>$this->key_box_closing_time,
         'staff_signature' =>$this->staff_signature,
         'purpose' =>$this->purpose,
         'dm_signature' =>$this->dm_signature,
         'register_key_one' =>$this->register_key_one,
         'key_one_staff_name' =>$this->key_one_staff_name,
         'key_two' =>$this->key_two,
         'key_two_staff_name' =>$this->key_two_staff_name,
         'safe_opening_time' =>$this->safe_opening_time,
         'safe_closing_time' =>$this->safe_closing_time,
         'key_one_staff_signature' =>$this->key_one_staff_signature,
         'key_two_staff_signature' =>$this->key_two_staff_signature,
         'remarks' =>$this->remarks,
         'register_key' =>$this->register_key,
         'register_number' =>$this->register_number,
         'register_make' =>$this->register_make,
         'register_number_held' =>$this->register_number_held,
         'register_issued' =>$this->register_issued,
         'position' =>$this->position,
         'employee_number' =>$this->employee_number,
         'date_of_issue' =>$this->date_of_issue,
         'date_of_return' =>$this->redate_of_returnarks,
         'recipient_signature' =>$this->recipient_signature,
         'name_of_vendor' =>$this->name_of_vendor,
         'delivery_challan_no' =>$this->delivery_challan_no,
         'invoice_no' =>$this->invoice_no,
         'description_of_items' =>$this->description_of_items,
         'qty' =>$this->qty,
         'seal_number' =>$this->seal_number,
         'total_number_of_passports' =>$this->total_number_of_passports,
         'sign_of_dispatcher' =>$this->sign_of_dispatcher,
         'checked_by_manager' =>$this->checked_by_manager,
         'supervisor' =>$this->supervisor,
         'employ_code' =>$this->employ_code,
         'secuirity_training_log_name' =>$this->secuirity_training_log_name,
         'department' =>$this->department,
         'vac' =>$this->vac,
         'facilitator' =>$this->facilitator,
         'location' =>$this->location,
         'details_of_incident' =>$this->details_of_incident,
         'inappropriate_behaviour_observed' =>$this->inappropriate_behaviour_observed,
         'location_in_the_vac' =>$this->location_in_the_vac,
         'reported_to' =>$this->reported_to,
         'action_taken' =>$this->action_taken,
         'remarks' =>$this->remarks,
         'name_of_guard' =>$this->name_of_guard,
         'checklist' =>$this->checklist,
         'type' =>$this->type,
         'weight' =>$this->weight,
         'refillig_date' =>$this->refillig_date,
         'expiry_date' =>$this->expiry_date,
         'inspection_date' =>$this->inspection_date,
         'next_due_date' =>$this->next_due_date,




         

        
        ];
    }
}
