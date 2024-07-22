<div class="settings-item w-100 confirm-wrap">
    <hr/>
    <div class="row m-0">
        <div class="col-12">
            <div data-simplebar>
                <div class="tab-content chat-list" id="pills-tabContent" >
                    <div class="tab-pane fade show active" id="tab1">
                        <div class="row m-0">
                            <div class="form-group col-md-6">
                                <label for="name">Name: </label>
                                <b>{{$obj->name}}</b>
                            </div>
                        </div>
                        <hr class="mt-0" />
                        <h6 class="ml-1">Permissions</h6>
                        <hr/>
                        <div class="row m-0">
                            @foreach($obj->permissions as $permissions)
                                <div class="form-group col-md-4">
                                    <div >
                                        <label ><i class="fas fa-check-square text-info"></i> {{$permissions->name}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</div>
