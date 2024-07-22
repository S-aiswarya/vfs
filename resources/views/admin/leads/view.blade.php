<div class="settings-item w-100 confirm-wrap">
    <div class="row m-0">
        <div class="col-12">
            <div data-simplebar>
                <div class="tab-content chat-list" id="pills-tabContent" >
                    <div class="tab-pane fade show active" id="tab1">
                        <div class="m-0">
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Status: </div>
                                    <div class="col-md-6">@if($obj->user_id) <span class="badge bg-success">Verified</span> @else <span class="badge bg-danger">Not Verified</span> @endif</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Lead Id: </div>
                                    <div class="col-md-6">{{$obj->id}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Name: </div>
                                    <div class="col-md-6">{{$obj->title}} {{$obj->name}}</div>
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
                                    <div class="col-md-6">Alternative Phone Number: </div>
                                    <div class="col-md-6">{{$obj->alternate_phone_number}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Whatsapp Number: </div>
                                    <div class="col-md-6">{{$obj->whatsapp_number}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Preferred Destinations: </div>
                                    <div class="col-md-6">{{$obj->preferred_destinations}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Preferred Packages: </div>
                                    <div class="col-md-6">{{$obj->preferred_packages}}</div>
                                </div>
                            </div>

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">City: </div>
                                    <div class="col-md-6">{{$obj->city}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Current Stage: </div>
                                    <div class="col-md-6">{{$obj->stage?->name}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Lead Source: </div>
                                    <div class="col-md-6">{{$obj->leadSource?->name}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Agency: </div>
                                    <div class="col-md-6">{{$obj->agency?->name}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Assigned To: </div>
                                    <div class="col-md-6">{{$obj->assignedTo?->name}}</div>
                                </div>
                            </div>
                            
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Office: </div>
                                    <div class="col-md-6">{{$obj->assignedToOffice?->name}}</div>
                                </div>
                            </div>
                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Achive Status: </div>
                                    <div class="col-md-6">@if($obj->closed) <span class="badge bg-danger">Archived</span> @else <span class="badge bg-success">Open</span> @endif</div>
                                </div>
                            </div>

                            @if($obj->closed)
                                <div class="view-pop">
                                    <div class="row">
                                        <div class="col-md-6">Archive Rote: </div>
                                        <div class="col-md-6">{{$obj->archive_note}}</div>
                                    </div>
                                </div>
                                <div class="view-pop">
                                    <div class="row">
                                        <div class="col-md-6">Archive Reason: </div>
                                        <div class="col-md-6">{{$obj->archive_reason}}</div>
                                    </div>
                                </div>
                            @endif
                            
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

                            <div class="view-pop">
                                <div class="row">
                                    <div class="col-md-6">Note: </div>
                                    <div class="col-md-6">{{$obj->note}}</div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</div>
