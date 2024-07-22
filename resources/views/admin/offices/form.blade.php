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
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
      </div>
      <div class="form-group">
        <label for="name">Code</label>
        <input type="text" class="form-control" name="code" value="{{$obj->code}}">
      </div>
      <div class="form-group">
            <label for="name">Country</label>
            <select name="country_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.countries')}}">
              @if($obj->country)
                <option value="{{$obj->country->id}}" selected="selected">{{$obj->country->name}}</option>
              @endif
            </select>
        </div>
      <div class="form-group">
        <label for="name">Address</label>
        <textarea class="form-control" name="address" rows="3">{{$obj->address}}</textarea>
      </div>
      <div class="form-group">
        <label for="name">Email Ids</label>
        <input type="text" class="form-control" id="email_ids" name="email_ids" value="{{$obj->email_ids}}">
      </div>
      <div class="form-group">
        <label for="name">Phone Numbers</label>
        <input type="text" class="form-control" id="phone_numbers" name="phone_numbers" value="{{$obj->phone_numbers}}">
      </div>
      @php
        $selected_users = [];
        if(count($obj->users))
          $selected_users = $obj->users->pluck('id')->toArray();
      @endphp
      @foreach($roles as $role)
        <div class="form-group">
          <label for="name">{{$role->name}}</label>
          <select name="user[]" multiple style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
              @foreach($role->users as $user)
                <option value="{{$user->id}}" @if(in_array($user->id, $selected_users)) selected="selected" @endif>{{$user->name}}</option>
              @endforeach
          </select>
        </div>
      @endforeach
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>