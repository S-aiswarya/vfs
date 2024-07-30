<div class="settings-item w-100 confirm-wrap">
  @include('admin._partials.notifications')
  @if($obj->id)
    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true>
  @else
    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true>
  @endif
  @csrf
  <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
    <div class="row">
      <div class="form-group col-12">
        <label for="name">Role</label>
        <select name="role_id" class="form-control" id="role_id">
          <option value="">Select</option>
          @foreach($roles as $role)
            <option value="{{$role->id}}" @if($obj->role_id == $role->id) selected="selected"@endif>{{$role->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
            <label for="name">Country</label>
            <select name="office_country_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.countries')}}">
              @if($obj->officeCountry)
                <option value="{{$obj->officeCountry->id}}" selected="selected">{{$obj->officeCountry->name}}</option>
              @endif
            </select>
        </div>

        <div class="form-group">
            <label for="center">Center</label>
            <select name="center_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.centers')}}">
              @if($obj->center)
                <option value="{{$obj->center->id}}" selected="selected">{{$obj->center->name}}</option>
              @endif
            </select>
        </div>
        
      <div id="branch-div" class="form-group col-12">
            <label for="name">Offices</label>
            @if($obj->role_id == 6)
              <select name="offices[]" multiple style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.branches')}}">
                @if(count($obj->applicationCoordinatorOffices))
                  @foreach($obj->applicationCoordinatorOffices as $office)
                    <option value="{{$office->id}}" selected="selected" >{{$office->name}}</option>
                  @endforeach
                @endif
              </select>
            @else
              <select name="offices[]" multiple style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.branches')}}">
                @if(count($obj->offices))
                  @foreach($obj->offices as $office)
                    <option value="{{$office->id}}" selected="selected" >{{$office->name}}</option>
                  @endforeach
                @endif
              </select>
            @endif
        </div>
      <div id="manager-div" class="form-group col-12" @if($obj->role_id != 5) style="display:none;" @endif>
            <label for="name">Manager</label>
            <select name="manager_id" id="manager_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.users', [4])}}">
              @if($obj->manager)
                <option value="{{$obj->manager->id}}" selected="selected">{{$obj->manager->name}}</option>
              @endif
            </select>
        </div>
      <div class="form-group col-12">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
      </div>
      <div class="form-group col-6">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{$obj->email}}">
      </div>
      <div class="form-group col-6">
        <label for="email">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" maxlength="20" name="phone_number" value="{{$obj->phone_number}}">
      </div>
      <div class="form-group col-6">
        <label for="email">Password</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <div class="form-group col-6">
        <label for="email">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
      </div>
      <div class="form-group">
        <label for="name">Address</label>
        <textarea class="form-control" name="address" rows="3">{{$obj->address}}</textarea>
      </div>
      @if($obj->role_id == 4)
      <div class="form-group col-12" >
          <label for="name">Counsellors</label>
          <select name="counsellors[]" multiple style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.users', [5])}}">
            @if(count($obj->counsellors))
              @foreach($obj->counsellors as $counsellor)
                <option value="{{$counsellor->id}}" selected="selected">{{$counsellor->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        @endif
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>