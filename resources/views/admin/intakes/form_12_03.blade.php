<div class="settings-item w-100 confirm-wrap">
  @include('admin._partials.notifications')
  @if($obj->id)
    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @else
    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true enctype="multipart/form-data">
  @endif
  @csrf
  <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
  <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Basic Details
        </button>
      </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <div class="row">
          <div class="form-group">
            <label for="name">University</label>
            <select name="university_id" id="university_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.universities')}}">
              @if($obj->university)
                <option value="{{$obj->university->id}}" selected="selected">{{$obj->university->name}}</option>
              @endif
            </select>
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$obj->name}}">
          </div>
          <div class="row m-0 form-group">
            <div class="col-6 pl-0">
              <label for="name">Month</label>
              @php
                $months = ['01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'May', '06'=>'Jun', '07'=>'Jul', '08'=>'Aug', '09'=>'Sep', '10'=>'Oct', '11'=>'Nov', '12'=>'Dec'];
              @endphp
              <select name="month" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap">
                <option value="">-- Select --</option>
                @foreach($months as $key=>$month)
                  <option value="{{$key}}" @if($obj->month == $key) selected="selected" @endif>{{$month}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6 pr-0">
              <label for="name">Year</label>
              <input type="integer" class="form-control" name="year" maxlength="4" minlength="4" value="{{$obj->year}}">
            </div>
          </div>
          <div class="row m-0 form-group">
            <div class="col-6 pr-0">
              <label for="name">Application end Date</label>
              <input type="text" class="form-control datepicker" name="application_end_date" @if($obj->application_end_date) value="{{date('d-m-Y', strtotime($obj->application_end_date))}}" @endif>
            </div>
            <div class="col-6 pr-0">
              <label for="name">Cas cut off Date</label>
              <input type="text" class="form-control datepicker" name="cas_cut_off_date" @if($obj->cas_cut_off_date) value="{{date('d-m-Y', strtotime($obj->cas_cut_off_date))}}" @endif>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Courses
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body" id="course-holder">
        @if($obj->university && count($obj->university->courses))
          @include('admin.intakes.courses', ['university_courses'=>$obj->university->courses, 'intake_courses'=>$obj->courses])
        @endif
      </div>
    </div>
  </div>
</div>
    
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>