@if(count($university_courses))
<div class="row m-0 float-right">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="checkedAll" @if(count($university_courses) == count($intake_courses)) checked="checked" @endif >
        <label class="custom-control-label" for="checkedAll" id="check-label">@if(count($university_courses) == count($intake_courses)) Deselect All @else Select All @endif</label>
    </div>
</div>
<div class="clearfix"></div> 
<hr/>
<div class="row">
        @php
          $selected_courses = [];
          if(count($intake_courses))
            $selected_courses = $intake_courses->pluck('id')->toArray();
        @endphp
        @foreach($university_courses as $uCourse)
          <div class="form-group col-md-6">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input checkSingle" value="{{$uCourse->id}}" id="role-{{$uCourse->id}}" name="course[]" @if(in_array($uCourse->id, $selected_courses)) checked="checked" @endif>
                <label class="custom-control-label" for="role-{{$uCourse->id}}">{{$uCourse->name}}</label>
              </div>
          </div>
      @endforeach
</div>
@endif