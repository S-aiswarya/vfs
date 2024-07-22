<div class="settings-item w-100 confirm-wrap">
    @include('admin._partials.notifications')
      <form method="POST" action="{{ route($route.'.next-stage-store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$id}}">
      <div class="row">
        @foreach($stages as $stage)
            <div class="form-group col-md-6 p-2">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" value="{{$stage->id}}" id="stage-{{$stage->id}}" name="stage_id[]" @if(in_array($stage->id, $next_possible_stages)) checked="checked" @endif>
                  <label class="custom-control-label" for="stage-{{$stage->id}}">{{$stage->name}}</label>
                </div>
            </div>
        @endforeach
      </div>
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-reload-type="hard">Submit</button>
        </div>
      </div>
    </form>
</div>