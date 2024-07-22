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
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{$obj->email}}">
      </div>
    </div>
    <h6 class="ml-1">Roles</h6>
    <hr>
    <div class="row">
        @foreach($roles as $role)
          <div class="form-group col-md-6">
              <div class="custom-control custom-switch">
                <input type="radio" class="custom-control-input checkSingle" value="{{$role->id}}" id="role-{{$role->id}}" name="role" @if($obj->hasRole($role->name)) checked="checked" @endif>
                <label class="custom-control-label" for="role-{{$role->id}}">{{$role->name}}</label>
              </div>
          </div>
      @endforeach
    </div>
    <hr>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>