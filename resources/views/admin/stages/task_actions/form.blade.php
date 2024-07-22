<div class="settings-item w-100 confirm-wrap">
    @include('admin._partials.notifications')
    @if($obj->id)
      <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
    @else
      <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
    @endif
    @csrf
    <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
    <input type="hidden" name="stage_id" value="{{$stage->id}}" />
      <div class="row">
        <div class="form-group">
          <label for="name">Title</label>
          <input type="text" class="form-control" name="title" value="{{$obj->title}}">
        </div>
        <div class="form-group">
          <label for="name">Description</label>
          <textarea class="form-control editor" name="description" rows="3">{{$obj->description}}</textarea>
        </div>
        <div class="form-group">
          <label for="name">Duration (in hours)</label>
          <input type="number" class="form-control" name="duration" value="{{$obj->duration}}">
        </div>
        <div class="form-group">
            <label for="name">Assign To</label>
            <select name="assign_to" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
                <option value="Manager" @if($obj->assign_to == "Manager") selected="selected" @endif >Manager</option>
                <option value="Sales Person" @if($obj->assign_to == "Sales Person") selected="selected" @endif >Sales Person</option>
            </select>
        </div>
        
      </div>
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
        </div>
      </div>
    </form>
</div>