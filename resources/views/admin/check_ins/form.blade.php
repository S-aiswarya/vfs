<div class="settings-item w-100 confirm-wrap">
  @include('admin._partials.notifications')
  <style>
  /* Basic styling for demonstration purposes */
  .form-step {
    display: none; /* Hide all steps initially */
  }
  .form-step.active {
    display: block; /* Show only the active step */
  }
</style>

  
</div>

@if($obj->id)
    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @else
    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @endif
  @csrf
  <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif >
  


    <!-- <div style=" text-align:center;">  -->
   @if(!empty($obj->country))
    <div class="row">
      <div class="col-5">Country</div>
      <div class="col-7">{{$obj->country->name}}</div>
  </div>   
  <hr/>
   @endif

   @if(!empty($obj->city))
   <div class="row">
     <div class="col-5">City</div>
      <div class="col-7">{{$obj->city->name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->center_location))
   <div class="row">
     <div class="col-5">Building</div>
      <div class="col-7">{{$obj->center_location->name}}</div>
  </div> 
  <hr>
  @endif

  
  @if(!empty($obj->center))
   <div class="row">
     <div class="col-5">Center</div>
      <div class="col-7">{{$obj->center->name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->gate))
   <div class="row">
     <div class="col-5">Gates/Floors</div>
      <div class="col-7">{{$obj->gate->name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_types))
   <div class="row">
     <div class="col-5">Register Types</div>
      <div class="col-7">{{$obj->register_types->register_name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->token))
   <div class="row">
     <div class="col-5">Token</div>
      <div class="col-7">{{$obj->token}}</div>
  </div>
  <hr> 
  @endif

  @if(!empty($obj->name))
   <div class="row">
     <div class="col-5">Name</div>
      <div class="col-7">{{$obj->name}}</div>
  </div> 
  <hr>
  @endif


  @if(!empty($obj->phonenumber))
   <div class="row">
     <div class="col-5">Phonenumber</div>
      <div class="col-7">{{$obj->phonenumber}}</div>
  </div> 
  <hr>
  @endif
   
  @if(!empty($obj->email))
   <div class="row">
     <div class="col-5">Email</div>
      <div class="col-7">{{$obj->email}}</div>
  </div>
  <hr> 
  @endif

  @if(!empty($obj->date))
   <div class="row">
     <div class="col-5">Date</div>
      <div class="col-7">{{$obj->date}}</div>
  </div>
  <hr> 
  @endif

  @if(!empty($obj->consignor))
   <div class="row">
     <div class="col-5">Consignor</div>
      <div class="col-7">{{$obj->consignor}}</div>
  </div>
  <hr> 
  @endif

  @if(!empty($obj->courier_agency))
   <div class="row">
     <div class="col-5">Courier_agency</div>
      <div class="col-7">{{$obj->courier_agency}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->awb_no))
   <div class="row">
     <div class="col-5">Awb No</div>
      <div class="col-7">{{$obj->awb_no}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->dated))
   <div class="row">
     <div class="col-5">Date</div>
      <div class="col-7">{{$obj->dated}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->zip_lock_no))
   <div class="row">
     <div class="col-5">ZipLock Number</div>
      <div class="col-7">{{$obj->zip_lock_no}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->time_of_receipt))
   <div class="row">
     <div class="col-5">Time of Receipt</div>
      <div class="col-7">{{$obj->time_of_receipt}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->shipment_handed_over_to))
   <div class="row">
     <div class="col-5">Shipment Handed Over To</div>
      <div class="col-7">{{$obj->shipment_handed_over_to}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->mobile_phone_unit_model))
   <div class="row">
     <div class="col-5">Mobile Phone Unit Model</div>
      <div class="col-7">{{$obj->mobile_phone_unit_model}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->time_deposited))
   <div class="row">
     <div class="col-5">Time Deposited</div>
      <div class="col-7">{{$obj->time_deposited}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->signature))
   <div class="row">
     <div class="col-5">Signature</div>
      <div class="col-7">{{$obj->signature}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->incident))
   <div class="row">
     <div class="col-5">Incident</div>
      <div class="col-7">{{$obj->incident}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->informed_to))
   <div class="row">
     <div class="col-5">Informed To</div>
      <div class="col-7">{{$obj->informed_to}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->mode))
   <div class="row">
     <div class="col-5">Mode</div>
      <div class="col-7">{{$obj->mode}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->guard))
   <div class="row">
     <div class="col-5">Name_of_Guard</div>
      <div class="col-7">{{$obj->guard}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_box))
   <div class="row">
     <div class="col-5">Key Box</div>
      <div class="col-7">{{$obj->key_box}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_number))
   <div class="row">
     <div class="col-5">Key number</div>
      <div class="col-7">{{$obj->key_number}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->name_of_staff))
   <div class="row">
     <div class="col-5">Name Of Staff</div>
      <div class="col-7">{{$obj->name_of_staff}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_box_opening_time))
   <div class="row">
     <div class="col-5">Key Box Opening Time</div>
      <div class="col-7">{{$obj->key_box_opening_time}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_box_closing_time))
   <div class="row">
     <div class="col-5">Key Box closing Time</div>
      <div class="col-7">{{$obj->key_box_closing_time}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->purpose))
   <div class="row">
     <div class="col-5">Purpose</div>
      <div class="col-7">{{$obj->purpose}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_key_one))
   <div class="row">
     <div class="col-5">Key One</div>
      <div class="col-7">{{$obj->register_key_one}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_one_staff_name))
   <div class="row">
     <div class="col-5">Key 1 Staff Name</div>
      <div class="col-7">{{$obj->key_one_staff_name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->key_two_staff_name))
   <div class="row">
     <div class="col-5">Key 2 Staff Name</div>
      <div class="col-7">{{$obj->key_two_staff_name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->safe_opening_time))
   <div class="row">
     <div class="col-5">Safe Opening Time</div>
      <div class="col-7">{{$obj->safe_opening_time}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->safe_closing_time))
   <div class="row">
     <div class="col-5">Safe Closing Time</div>
      <div class="col-7">{{$obj->safe_closing_time}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->remarks))
   <div class="row">
     <div class="col-5">Remarks</div>
      <div class="col-7">{{$obj->remarks}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_key))
   <div class="row">
     <div class="col-5">Key</div>
      <div class="col-7">{{$obj->register_key}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_number))
   <div class="row">
     <div class="col-5">Number/Make</div>
      <div class="col-7">{{$obj->register_number}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_number_held))
   <div class="row">
     <div class="col-5">Number Held</div>
      <div class="col-7">{{$obj->register_number_held}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->register_issued))
   <div class="row">
     <div class="col-5">Issued to (Name)</div>
      <div class="col-7">{{$obj->register_issued}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->position))
   <div class="row">
     <div class="col-5">Position</div>
      <div class="col-7">{{$obj->position}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->employee_number))
   <div class="row">
     <div class="col-5">Employee Number</div>
      <div class="col-7">{{$obj->employee_number}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->date_of_issue))
   <div class="row">
     <div class="col-5">Date Of Issue</div>
      <div class="col-7">{{$obj->date_of_issue}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->date_of_return))
   <div class="row">
     <div class="col-5">Date Of Return</div>
      <div class="col-7">{{$obj->date_of_return}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->name_of_vendor))
   <div class="row">
     <div class="col-5">Name Of Vendor</div>
      <div class="col-7">{{$obj->name_of_vendor}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->delivery_challan_no))
   <div class="row">
     <div class="col-5">Delivery Challan Number</div>
      <div class="col-7">{{$obj->delivery_challan_no}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->delivery_challan_date))
   <div class="row">
     <div class="col-5">Delivery Challan Date</div>
      <div class="col-7">{{$obj->delivery_challan_date}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->invoice_date))
   <div class="row">
     <div class="col-5">Invoice Date</div>
      <div class="col-7">{{$obj->invoice_date}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->invoice_no))
   <div class="row">
     <div class="col-5">Invoice Number</div>
      <div class="col-7">{{$obj->invoice_no}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->description_of_items))
   <div class="row">
     <div class="col-5">Description Of Items</div>
      <div class="col-7">{{$obj->description_of_items}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->consignee))
   <div class="row">
     <div class="col-5">Consignee</div>
      <div class="col-7">{{$obj->consignee}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->gate_pass_number))
   <div class="row">
     <div class="col-5">Gate Pass Number</div>
      <div class="col-7">{{$obj->gate_pass_number}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->authorized))
   <div class="row">
     <div class="col-5">Authorized</div>
      <div class="col-7">{{$obj->authorized}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->If_returnable_expected_date_of_return))
   <div class="row">
     <div class="col-5">If Returnable Expected Date Of Return</div>
      <div class="col-7">{{$obj->If_returnable_expected_date_of_return}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->qty))
   <div class="row">
     <div class="col-5">Quantity</div>
      <div class="col-7">{{$obj->qty}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->full_name_and_address_of_recipient))
   <div class="row">
     <div class="col-5">Full Name And Address Of Recipient</div>
      <div class="col-7">{{$obj->full_name_and_address_of_recipient}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->received_by))
   <div class="row">
     <div class="col-5">Received By</div>
      <div class="col-7">{{$obj->received_by}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->seal_number))
   <div class="row">
     <div class="col-5">Seal Number</div>
      <div class="col-7">{{$obj->seal_number}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->total_number_of_passports))
   <div class="row">
     <div class="col-5">Total Number Of Passports</div>
      <div class="col-7">{{$obj->total_number_of_passports}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->checked_by_manager))
   <div class="row">
     <div class="col-5">Checked By Manager</div>
      <div class="col-7">{{$obj->checked_by_manager}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->supervisor))
   <div class="row">
     <div class="col-5">Supervisor</div>
      <div class="col-7">{{$obj->supervisor}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->employ_code))
   <div class="row">
     <div class="col-5">Employ Code</div>
      <div class="col-7">{{$obj->employ_code}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->secuirity_training_log_name))
   <div class="row">
     <div class="col-5">Secuirity Training Log Name</div>
      <div class="col-7">{{$obj->secuirity_training_log_name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->department))
   <div class="row">
     <div class="col-5">Department</div>
      <div class="col-7">{{$obj->department}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->vac))
   <div class="row">
     <div class="col-5">Vac</div>
      <div class="col-7">{{$obj->vac}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->staff_one_name_check_in))
   <div class="row">
     <div class="col-5">Staff One Name Check In</div>
      <div class="col-7">{{$obj->staff_one_name_check_in}}</div>
  </div> 
  <hr>
  @endif
  
  @if(!empty($obj->staff_two_name_check_in))
   <div class="row">
     <div class="col-5">Staff Two Name Check In</div>
      <div class="col-7">{{$obj->staff_two_name_check_in}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->staff_one_name_check_out))
   <div class="row">
     <div class="col-5">Staff One Name Check Out</div>
      <div class="col-7">{{$obj->staff_one_name_check_out}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->staff_two_name_check_out	))
   <div class="row">
     <div class="col-5">Staff Two Name Check Out</div>
      <div class="col-7">{{$obj->staff_two_name_check_out	}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->facilitator))
   <div class="row">
     <div class="col-5">Facilitator</div>
      <div class="col-7">{{$obj->facilitator}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->name_of_the_visitor))
   <div class="row">
     <div class="col-5">Name Of The Visitor</div>
      <div class="col-7">{{$obj->name_of_the_visitor}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->business_address_of_visitor))
   <div class="row">
     <div class="col-5">Business Address Of Visitor</div>
      <div class="col-7">{{$obj->business_address_of_visitor}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->national_id_type))
   <div class="row">
     <div class="col-5">National Id Type</div>
      <div class="col-7">{{$obj->national_id_type}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->whom_to_visit))
   <div class="row">
     <div class="col-5">Whom To Visit</div>
      <div class="col-7">{{$obj->whom_to_visit}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->purpose_of_visit))
   <div class="row">
     <div class="col-5">Purpose Of Visit</div>
      <div class="col-7">{{$obj->purpose_of_visit}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->visitor_pass_no))
   <div class="row">
     <div class="col-5">Visitor Pass No</div>
      <div class="col-7">{{$obj->visitor_pass_no}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->signature_of_visitor))
   <div class="row">
     <div class="col-5">signature_of_visitor</div>
      <div class="col-7">{{$obj->signature_of_visitor}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->medium_of_training))
   <div class="row">
     <div class="col-5">Medium Of Training</div>
      <div class="col-7">{{$obj->medium_of_training}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->location))
   <div class="row">
     <div class="col-5">Location</div>
      <div class="col-7">{{$obj->location}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->details_of_incident))
   <div class="row">
     <div class="col-5">Details Of Incident</div>
      <div class="col-7">{{$obj->details_of_incident}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->inappropriate_behaviour_observed))
   <div class="row">
     <div class="col-5">Inappropriate Behaviour Observed</div>
      <div class="col-7">{{$obj->inappropriate_behaviour_observed}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->location_in_the_vac))
   <div class="row">
     <div class="col-5">Location In The Vac</div>
      <div class="col-7">{{$obj->location_in_the_vac}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->reported_to))
   <div class="row">
     <div class="col-5">Reported To</div>
      <div class="col-7">{{$obj->reported_to}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->action_taken))
   <div class="row">
     <div class="col-5">Action Taken</div>
      <div class="col-7">{{$obj->action_taken}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->name_of_guard))
   <div class="row">
     <div class="col-5">Name Of Guard</div>
      <div class="col-7">{{$obj->name_of_guard}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->id_code))
   <div class="row">
     <div class="col-5">ID Code</div>
      <div class="col-7">{{$obj->id_code}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->agency_name))
   <div class="row">
     <div class="col-5">Agency Name</div>
      <div class="col-7">{{$obj->agency_name}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->checklist))
   <div class="row">
     <div class="col-5">Checklist</div>
      <div class="col-7">{{$obj->checklist}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->staff_one))
   <div class="row">
     <div class="col-5">Staff One</div>
      <div class="col-7">{{$obj->staff_one}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->staff_two))
   <div class="row">
     <div class="col-5">Staff Two</div>
      <div class="col-7">{{$obj->staff_two}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->type))
   <div class="row">
     <div class="col-5">Type</div>
      <div class="col-7">{{$obj->type}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->weight))
   <div class="row">
     <div class="col-5">Weight</div>
      <div class="col-7">{{$obj->weight}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->refillig_date))
   <div class="row">
     <div class="col-5">Refillig Date</div>
      <div class="col-7">{{$obj->refillig_date}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->expiry_date))
   <div class="row">
     <div class="col-5">Expiry Date</div>
      <div class="col-7">{{$obj->expiry_date}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->inspection_date))
   <div class="row">
     <div class="col-5">Inspection Date</div>
      <div class="col-7">{{$obj->inspection_date}}</div>
  </div> 
  <hr>
  @endif

  @if(!empty($obj->next_due_date))
   <div class="row">
     <div class="col-5">Next Due Date</div>
      <div class="col-7">{{$obj->next_due_date}}</div>
  </div> 
  <hr>
  @endif

  
  





  @if(!empty($obj->entry_time))
   <div class="row">
     <div class="col-5">Entry Time</div>
      <div class="col-7">{{$obj->entry_time}}</div>
  </div> 
  <hr>
  @endif
  @if(!empty($obj->exit_time))
   <div class="row">
     <div class="col-5">Exit Time</div>
      <div class="col-7">{{$obj->exit_time}}</div>
  </div> 
  <hr>
  @endif

   </div>

</form>

<script>
  // Function to show next step
  function nextStep(currentStep) {
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.getElementById(`step-${currentStep + 1}`).classList.add('active');
  }

  // Function to show previous step
  function prevStep(currentStep) {
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.getElementById(`step-${currentStep - 1}`).classList.add('active');
  }
</script>
<script></script>