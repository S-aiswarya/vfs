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
            <label for="name">Country</label>
            <select name="country_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.global-countries')}}">
              @if($obj->country)
                <option value="{{$obj->country->id}}" selected="selected">{{$obj->country->name}}</option>
              @endif
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
        </div>
        <div class="form-group form-group-image-upload">
            <label for="name">Logo</label>
            @include('admin._partials.image_upload', ['id'=> 'logo', 'name' => 'logo', 'image' => $obj->logo])
        </div>
        <div class="form-group">
            <label for="name">Extra University Info</label>
            <textarea name="extra_university_info" class="form-control">{{$obj->extra_university_info}}</textarea>
        </div>
        <div class="form-group">
            <label for="name">Extra Scholarship Info</label>
            <textarea name="extra_scholarship_info" class="form-control">{{$obj->extra_scholarship_info}}</textarea>
        </div>
        <div class="form-group">
            <label for="name">Portal Link</label>
            <input type="text" class="form-control" name="portal_link" value="{{$obj->portal_link}}">
        </div>
        <div class="form-group">
            <label for="name">Portal Username</label>
            <input type="text" class="form-control" name="portal_username" value="{{$obj->portal_username}}">
        </div>
        <div class="form-group">
            <label for="name">Portal Password</label>
            <input type="text" class="form-control" name="portal_password" value="{{$obj->portal_password}}">
        </div>
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>