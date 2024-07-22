<div class="settings-item w-100 confirm-wrap">
    <hr/>
    <div class="row m-0">
        <div class="col-12">
            <div data-simplebar>
                <div class="tab-content chat-list" id="pills-tabContent" >
                    <div class="tab-pane fade show active" id="tab1">
                        <div class="m-0">
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Lead Id: </div>
                                    <div class="col-md-6">{{$obj->lead->id}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Name: </div>
                                    <div class="col-md-6">{{$obj->name}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Email: </div>
                                    <div class="col-md-6">{{$obj->email}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Phone Number: </div>
                                    <div class="col-md-6">{{$obj->phone_number}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Citizenship Country: </div>
                                    <div class="col-md-6">{{$obj->citizenshipCountry?->name}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Address: </div>
                                    <div class="col-md-6">{{$obj->address}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Passport Number: </div>
                                    <div class="col-md-6">{{$obj->passport_number}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Passport Exp. Date: </div>
                                    <div class="col-md-6">{{$obj->passport_exp_date}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Remarks: </div>
                                    <div class="col-md-6">{{$obj->remarks}}</div>
                                </div>
                            </div>

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Stage: </div>
                                    <div class="col-md-6">{{$obj->stage->name}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Created By: </div>
                                    <div class="col-md-6">{{$obj->created_user?->name}}</div>
                                </div>
                            </div>

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Last Updated By: </div>
                                    <div class="col-md-6">{{$obj->updated_user?->name}}</div>
                                </div>
                            </div>

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Created On: </div>
                                    <div class="col-md-6">{{date('d M, Y h:i A', strtotime($obj->created_at))}}</div>
                                </div>
                            </div>

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Last Updated On: </div>
                                    <div class="col-md-6">{{date('d M, Y h:i A', strtotime($obj->updated_at))}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</div>
