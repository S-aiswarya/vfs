<div class="settings-item w-100 confirm-wrap">
    @include('admin._partials.notifications')
      <form method="POST" action="{{ route($route.'.modify-status-store') }}" class="p-t-15" id="StatusFrm" data-validate=true>
    @csrf
      <input type="hidden" name="id" value="{{$obj->id}}" id="inputId">
      <div class="row">
        <div class="form-group col-12">
          <label for="name">Hand over assignments to: </label>
          <select name="user_id" class="form-control" id="user_id">
            <option value="">Select</option>
            @foreach($users as $user)
              <option value="{{$user->id}}">{{$user->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-validation-fn="statusValidate" data-reload-type="soft">Submit</button>
        </div>
      </div>
    </form>
</div>