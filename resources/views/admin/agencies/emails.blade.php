<div class="settings-item w-100 confirm-wrap">
  @include('admin._partials.notifications')
    <form method="POST" action="{{ route($route.'.emails.store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="id" value="{{encrypt($id)}}">
    @if(count($emails))
        @foreach($emails as $email)
        <div class="row list-item">
            <div class="form-group col-6 p-0">
                <label for="name">Email Address</label>
                <input type="text" class="form-control" name="email[]" value="{{$email->email}}">
            </div>
            <div class="form-group col-5">
                <label for="name">Label</label>
                <input type="text" class="form-control" name="label[]" value="{{$email->label}}">
            </div>
            <div class="form-group col-1">
                <a href="javascript:void(0);" class="btn btn-danger mt-4 remove-row">X</a>
            </div>
        </div>
        @endforeach
    @endif
    <div class="row">
        <div class="form-group col-6 p-0">
            <label for="name">Email Address</label>
            <input type="text" class="form-control" name="email[]">
        </div>
        <div class="form-group col-5">
            <label for="name">Label</label>
            <input type="text" class="form-control" name="label[]">
        </div>
        <div class="form-group col-1">
            
        </div>
    </div>
    <div class="row bottom-btn">
      <div class="col p-0">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
      <div class="col p-0 text-right">
          <button type="button" class="btn btn-success px-4" id="add-new-email"><i class="fas fa-plus-circle"></i></button>
      </div>
    </div>
  </form>
</div>