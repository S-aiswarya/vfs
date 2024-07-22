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
          <div class="form-group col-6 pl-0">
              <label for="name">Month</label>
              @php
                $months = ['Jan'=>'Jan', 'Jul'=>'Jul', 'Sep'=>'Sep'];
              @endphp
              <select name="month" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
                <option value="">-- Select --</option>
                @foreach($months as $key=>$month)
                  <option value="{{$key}}" @if($obj->month == $key) selected="selected" @endif>{{$month}}</option>
                @endforeach
              </select>
          </div>
          <div class="form-group col-6 pr-0">
              <label for="name">Year</label>
              <select name="year" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
                <option value="">-- Select --</option>
                @php
                  $year = date('Y');
                @endphp
                @for($i=$year; $i<=$year+2; $i++)
                  <option value="{{$i}}" @if($obj->year == $i) selected="selected" @endif>{{$i}}</option>
                @endfor
              </select>
          </div>
          <div class="form-group">
            <label for="name">Note</label>
            <textarea name="note" class="form-control">{{$obj->note}}</textarea>
        </div>
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>