<div class="settings-item w-100 confirm-wrap">
  @if($obj->has_system_settings == 0)
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
            <label for="name">Type</label>
            <select name="type" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
              <option value="Lead" @if($obj->type == "Lead") selected="selected" @endif>Lead</option>
              <option value="Visa" @if($obj->type == "Visa") selected="selected" @endif>Visa</option>
            </select>
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
        </div>
        <div class="form-group">
          <label for="name">Order</label>
          <input type="number" class="form-control" id="processing_order" name="processing_order" value="{{$obj->processing_order}}">
        </div>
        <div class="form-group">
          <label for="name">Colour</label>
          <input type="text" class="form-control" name="colour" value="{{$obj->colour}}">
        </div>
        <div class="form-group">
          <label for="name">Description</label>
          <textarea class="form-control" name="description" rows="3">{{$obj->description}}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
        </div>
      </div>
    </form>
  @else
  <div class="row">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" disabled value="{{$obj->name}}">
        </div>
        <div class="form-group">
          <label for="name">Order</label>
          <input type="text" class="form-control" id="processing_order" name="processing_order" disabled value="{{$obj->processing_order}}">
        </div>
  </div>
  @endif
</div>