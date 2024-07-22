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
            <label for="name">Email Template</label>
            <select name="email_template_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.email-templates')}}">
              @if($obj->emailTemplate)
                <option value="{{$obj->emailTemplate->id}}" selected="selected">{{$obj->emailTemplate->name}}</option>
              @endif
            </select>
        </div>
        <div class="form-group">
            <label for="name">Send To</label>
            <select name="send_to" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
                <option value="Manager" @if($obj->send_to == "Manager") selected="selected" @endif >Manager</option>
                <option value="Sales Person" @if($obj->send_to == "Sales Person") selected="selected" @endif >Sales Person</option>
                <option value="Lead" @if($obj->send_to == "Lead") selected="selected" @endif >Lead</option>
            </select>
        </div>
        <div class="form-group">
          <label for="name">CC to</label>
          <input type="text" class="form-control" id="cc_to" name="cc_to" value="{{$obj->cc_to}}">
        </div>
      </div>
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
        </div>
      </div>
    </form>
</div>