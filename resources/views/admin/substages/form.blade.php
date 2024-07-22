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
            <label for="name">Parant Stage</label>
            <select name="parent_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.stages')}}">
              @if($obj->parent)
                <option value="{{$obj->parent->id}}" selected="selected">{{$obj->parent->name}}</option>
              @else
                <option value="{{$stage->id}}" selected="selected">{{$stage->name}}</option>
              @endif
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
        {{--<div class="form-group">
            <label for="name">Action Type</label>
            <select name="action_type" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
              @foreach($action_types as $type)
                <option value="{{$type}}" @if($obj->action_type == $type) selected="selected" @endif >{{$type}}</option>
              @endforeach
            </select>
        </div>--}}
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
</div>