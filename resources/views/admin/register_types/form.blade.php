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
        <label for="register_name">Register name</label>
        <input type="text" class="form-control" id="register_name" name="register_name" value="{{$obj->register_name}}">
      </div>

      <div class="form-group">
          <label for="group_name">Group name</label>
            <input type="text" class="form-control" id="group_name" name="group_name" value="{{$obj->group_name}}">
      </div>


      <div class="form-group">
            <label for="name">Checkout_required</label>
               <select name="checkin_required" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" >
               <option value="">Select</option>
                <option value="1" @if($obj->checkin_required == 1) selected="selected" @endif >Yes</option>
                <option value="0"   @if($obj->checkin_required == 0) selected="selected" @endif >No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Key</label>
               <select name="key_id" style="width: 100% !important;" class="w-100 webadmin-select2-input form-control" data-parent=".confirm-wrap" data-select2-url="{{route('admin.select2.key_types')}}">
                @if($obj->key_types)
                <option value="{{$obj->key_types->id}}"  selected="selected">{{$obj->key_types->key_name}}</option>
                @endif
            </select>
        </div>


        <div class="form-group">
           <label for="name">Check Out</label>
                 <input type="datetime-local" name="check_out" id="check_out" class="form-control" value="{{$obj->check_out}}">
         </div>


         <div class="form-group">
           <label for="name">Sort Order</label>
                 <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{$obj->sort_order}}">
         </div>
      
    </div>
    <div class="row">
      <div class="text-right">
          <button type="submit" class="btn btn-primary px-4" data-validation-fn="adminValidate" data-reload-type="hard">Submit</button>
      </div>
    </div>
  </form>
</div>