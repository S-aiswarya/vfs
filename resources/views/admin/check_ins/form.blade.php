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
  
   <div class="form-step active" id="step-1">
    
  
  <div class="form-group">
      <label for="country">Country</label>
      <select name="country_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.countries')}}">
        @if($obj->country)
          <option value="{{$obj->country->id}}" selected="selected">{{$obj->country->name}}</option>
        @endif
      </select>
  </div> 

  <!-- City -->
  <div class="form-group">
      <label for="city">City</label>
      <select name="city_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.cities')}}">
        @if($obj->city)
          <option value="{{$obj->city->id}}" selected="selected">{{$obj->city->name}}</option>
        @endif
      </select>
  </div> 


  <!-- Location -->
  <div class="form-group">
      <label for="location">Location</label>
      <select name="location_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.locations')}}">
        @if($obj->location)
          <option value="{{$obj->location->id}}" selected="selected">{{$obj->location->name}}</option>
        @endif
      </select>
  </div> 

  <!-- Center -->
  <div class="form-group">
      <label for="name">Center</label>
      <select name="center_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.centers')}}">
        @if($obj->center)
          <option value="{{$obj->center->id}}" selected="selected">{{$obj->center->name}}</option>
        @endif
      </select>
  </div> 

  <!-- Gate -->
  <div class="form-group">
      <label for="gate">Gate</label>
      <select name="gate_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.gates')}}">
        @if($obj->gate)
          <option value="{{$obj->gate->id}}" selected="selected">{{$obj->gate->name}}</option>
        @endif
      </select>
  </div> 

   <!-- type of check -->
  <!-- <div class="form-group">
      <label for="type_check_id">Type Of Check_In</label>
      <select name="check_in_type_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.check_in_type')}}">
        @if($obj->register_type)
          <option value="{{$obj->check_in_type->id}}" selected="selected">{{$obj->check_in_type->name}}</option>
        @endif
      </select>
  </div>  -->

  <div class="form-group">
      <label for="type_check_id">Type Of Check_In</label>
      <select name="check_in_type_id" id="select" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.register_types')}}">
        @if($obj->register_types)
          <option value="{{$obj->register_types->id}}" selected="selected">{{$obj->register_types->register_name}}</option>
        @endif
      </select>
  </div>


  <div class="form-group">
      <label for="name">Entry Time</label>
        <input type="datetime-local" name="entry_time" id="entry_time" class="form-control" value="{{$obj->entry_time}}">
  </div>

  <div class="form-group">
      <label for="name">Exit Time</label>
        <input type="datetime-local" name="exit_time" id="exit_time" class="form-control" value="{{$obj->exit_time}}">
        </div>
        <button type="button" class="btn btn-primary px-4" onclick="nextStep(1)">Next</button>

</div>

   

  
</div>
  
 
  
  <!-- Step 2 -->
  <div class="form-step" id="step-2">
    <div class="form-group " >


                 <label for="name">Name</label>
                   <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
           </div>

            <div class="form-group">
                <label for="email">Email</label>
                   <input type="email" class="form-control" id="email" name="email" value="{{$obj->email}}">
             </div>

            <div class="form-group">
                <label for="email">Phone number</label>
                   <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="{{$obj->phonenumber}}">
             </div>
              


    <button type="button" class="btn btn-primary px-4" onclick="prevStep(2)">Previous</button>
    <button  type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
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