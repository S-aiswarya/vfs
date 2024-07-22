<div class="settings-item w-100 confirm-wrap">
    @include('admin._partials.notifications')
      <form method="POST" action="{{ route($route.'.targets-store') }}" class="p-t-15" id="TargetFrm" data-validate=true>
    @csrf
      <input type="hidden" name="id" value="{{$user_id}}" id="inputId">
      <div class="row">
        <div class="form-group col-12">
          <label for="name">Intake</label>
          <select name="intake_id" class="form-control" id="intake_id">
            <option value="">Select</option>
            @foreach($intakes as $intake)
              <option value="{{$intake->id}}" data-url="{{route('admin.users.targets', [$user_id, $intake->id])}}" @if($selected_intake == $intake->id) selected="selected"@endif>{{$intake->month.' '.$intake->year}}</option>
            @endforeach
          </select>
        </div>
        <div id="target_sec" class="row">
          @if($selected_intake)
            @if($user->role_id == 5)
              <div class="form-group col-6">
                <label>Application Submitted</label>
                <input type="number" class="form-control" name="application_submitted" @if($user_targets) value="{{$user_targets->application_submitted}}" @endif>
              </div>
              <div class="form-group col-6">
                <label>Unconditional Offers</label>
                <input type="number" class="form-control" name="unconditional_offers" @if($user_targets) value="{{$user_targets->unconditional_offers}}" @endif>
              </div>
              <div class="form-group col-6">
                <label>Deposit Paid</label>
                <input type="number" class="form-control" name="deposit_paid" @if($user_targets) value="{{$user_targets->deposit_paid}}" @endif>
              </div>
              <div class="form-group col-6">
                <label>Visa Obtained</label>
                <input type="number" class="form-control" name="visa_obtained" @if($user_targets) value="{{$user_targets->visa_obtained}}" @endif>
              </div>
            @else
              <div class="card w-100">
                <div class="card-header pr-2 pl-2">
                  Total
                </div>
                <div class="card-body row">
                  <div class="form-group col-6">
                    <label>Application Submitted : <b>@if($user_targets) {{$user_targets->application_submitted}} @endif</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Unconditional Offers : <b>@if($user_targets) {{$user_targets->unconditional_offers}} @endif</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Deposit Paid : <b>@if($user_targets) {{$user_targets->deposit_paid}} @endif</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Visa Obtained : <b>@if($user_targets) {{$user_targets->visa_obtained}} @endif</b></label>
                  </div>
                </div>
              </div>
              @foreach($user->counsellors as $counsellor)
              <div class="card w-100">
                <div class="card-header pr-2 pl-2">
                  {{$counsellor->name}}
                </div>
                <div class="card-body row">
                  <div class="form-group col-6">
                    <label>Application Submitted : <b>{{$counsellor->userTargetsApplicationSubmitted($selected_intake)}}</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Unconditional Offers : <b>{{$counsellor->userTargetsUnconditionalOffers($selected_intake)}}</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Deposit Paid : <b>{{$counsellor->userTargetsDepositPaid($selected_intake)}}</b></label>
                  </div>
                  <div class="form-group col-6">
                    <label>Visa Obtained : <b>{{$counsellor->userTargetsVisaObtained($selected_intake)}}</b></label>
                  </div>
                </div>
              </div>
              @endforeach
            @endif
          @endif
        </div>
      </div>
      @if($selected_intake && $user->role_id == 5)
      <div class="row">
        <div class="text-right">
            <button type="submit" class="btn btn-primary px-4" data-validation-fn="targetValidate" data-reload-type="hard">Submit</button>
        </div>
      </div>
      @endif
    </form>
</div>