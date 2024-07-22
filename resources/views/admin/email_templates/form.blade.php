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
        <label for="name">Mail Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" value="{{$obj->subject}}">
      </div>
      <div class="form-group">
        <label for="name">Mail Body <a href="javascript:void()" id="param-btn">Parameters</a></label>
        <div class="row" style="display: none;" id="params-list">
          <p class="col-6">@{{1}} : Name</p>
          <p class="col-6">@{{2}} : Email</p>
          <p class="col-6">@{{3}} : Phone number</p>
          <p class="col-6">@{{4}} : Alternate phone number</p>
          <p class="col-6">@{{5}} : Whatsapp number</p>
          <p class="col-6">@{{6}} : Preferred destinations</p>
          <p class="col-6">@{{7}} : Preferred packages</p>
          <p class="col-6">@{{8}} : Lead source</p>
          <p class="col-6">@{{9}} : How do you hear about us</p>
          <p class="col-6">@{{10}} : Note</p>
          <p class="col-6">@{{11}} : Assign to user</p>
          <p class="col-6">@{{12}} : Assign to office</p>
          <p class="col-6">@{{13}} : Current stage</p>
          <p class="col-6">@{{14}} : Assign to user address</p>
          <p class="col-6">@{{15}} : Assign to office address</p>
          <p class="col-6">@{{30}} : Traveler name</p>
          <p class="col-6">@{{31}} : Traveler email</p>
          <p class="col-6">@{{32}} : Traveler phone number</p>
          <p class="col-6">@{{33}} : Traveler citizenship country</p>
          <p class="col-6">@{{34}} : Traveler address</p>
          <p class="col-6">@{{35}} : Traveler passport number</p>
          <p class="col-6">@{{36}} : Traveler passport exp. date</p>
          <p class="col-6">@{{37}} : Traveler visa stage</p>
        </div>
        <textarea class="form-control editor" name="body" rows="3">{{$obj->body}}</textarea>
      </div>
      <div class="form-group">
        <label for="name">Mail Body Footer</label>
        <textarea class="form-control editor" name="body_footer" rows="3">{{$obj->body_footer}}</textarea>
      </div>
      <div class="form-group">
        <label for="name">Default cc</label>
        <input type="text" class="form-control" id="default_cc" name="default_cc" value="{{$obj->default_cc}}">
      </div>
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>