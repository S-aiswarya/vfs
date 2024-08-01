<div class="settings-item w-100 confirm-wrap">
  @include('admin._partials.notifications')
  @if($obj->id)
    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @else
    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @endif
  @csrf
  <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
    <div class="row">

    <div class="form-group">
            <label for="user">user</label>
            <select name="user_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.users')}}">
              @if($obj->user)
                <option value="{{$obj->user->id}}" selected="selected">{{$obj->user->name}}</option>
              @endif
            </select>
        </div>

     
        
       <!-- country -->
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
        @if($obj->center_location)
          <option value="{{$obj->center_location->id}}" selected="selected">{{$obj->center_location->name}}</option>
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
      
  <div class="form-group">
        <label for="name">Sign_In_Time</label>
        <input type="datetime-local" class="form-control" id="sign_in_time" name="sign_in_time" value="{{$obj->sign_in_time}}">
      </div>


      <div class="form-group">
        <label for="name">Sign_Out_Time</label>
        <input type="datetime-local" class="form-control" id="sign_out_time" name="sign_out_time" value="{{$obj->sign_out_time}}">
      </div>
        
      
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>