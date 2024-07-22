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
            <label for="name">Course Level</label>
            <select name="course_level_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.course-levels')}}">
              @if($obj->course_level)
                <option value="{{$obj->course_level->id}}" selected="selected">{{$obj->course_level->name}}</option>
              @endif
            </select>
      </div>
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
      </div>
      <div class="form-group form-group-image-upload">
        <label for="name">Image</label>
        @include('admin._partials.image_upload', ['id'=> 'image', 'name' => 'image', 'image' => $obj->image])
      </div>
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>