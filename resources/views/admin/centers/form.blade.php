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
            <label for="location">Buildings</label>
            <select name="location_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.locations')}}">
              @if($obj->location)
                <option value="{{$obj->location->id}}" selected="selected">{{$obj->location->name}}</option>
              @endif
            </select>
        </div>

      <div class="form-group">
        <label for="name">Center Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
      </div>

      <div class="form-group">
        <label for="name">Token</label>
        <input type="text" class="form-control" id="token_prefix" name="token_prefix" value="{{$obj->token_prefix}}">
      </div>
        
      <div class="form-group col-md-12">
        <label>Address</label>
          <textarea name="address" class="form-control" rows="2" id="address">{{$obj->address}}</textarea>
      </div>
      
    </dCeniv>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>