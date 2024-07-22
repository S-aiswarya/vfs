@extends('admin._layouts.fileupload')
@section('content')
<!-- Top Bar Start -->
            <div class="topbar">            
                <!-- Navbar -->
                <nav class="navbar-custom">    
                    @include('admin._partials.profile_menu')
        
                    <ul class="list-unstyled topbar-nav mb-0">                        
                        <li>
                            <button class="nav-link button-menu-mobile">
                                <i data-feather="menu" class="align-self-center topbar-icon"></i>
                            </button>
                        </li> 
                          
                    </ul>
                </nav>
                <!-- end navbar-->
            </div>
            <!-- Top Bar End -->

            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">View Lead Details</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route($route.'.index') }}">All Leads</a></li>
                                            <li class="breadcrumb-item active">View Lead Details</li>
                                        </ol>
                                    </div><!--end col-->
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    
                    <div class="row">
                        <div class="col-lg-12">
                            @include('admin._partials.notifications')
                                    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true>
                                    @csrf
                                    <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div data-simplebar>
                                                        <div class="row m-0">
                                                            <div class="form-group col-md-6">
                                                                <label>Name: </label>
                                                                <b>{{$obj->name}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Phone Number: </label>
                                                                <b>{{$obj->phone_number}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">Email: </label>
                                                                <b>{{$obj->email}}</b>
                                                            </div>
                                                            @if($obj->extra_data)
                                                                @php
                                                                    $extra_data = json_decode($obj->extra_data);
                                                                @endphp
                                                                @foreach($extra_data as $eData)
                                                                    <div class="form-group col-md-6">
                                                                        <label class="">{{$eData->key}}: </label>
                                                                        {{$eData->value}}
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div class="form-group col-md-12">
                                                                <label>Message: </label>
                                                                {{$obj->message}}
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Lead Type: </label>
                                                                {{$obj->lead_type}}
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Utm Source: </label>
                                                                {{$obj->utm_source}}
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">Source Url: </label>
                                                                <b>{{$obj->source_url}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">IP Address: </label>
                                                                <b>{{$obj->ip_address}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">User Agent: </label>
                                                                <b>{{$obj->user_agent}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">Referrer Link: </label>
                                                                <b>{{$obj->referrer_link}}</b>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="">Status: </label>
                                                                @if($obj->status == 'Open')
                                                                    <span class="badge text-danger">Open</span>
                                                                @else
                                                                    <span class="badge text-success">Close</span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label class="">Remarks: </label>
                                                                <b>{{$obj->remarks}}</b>
                                                            </div>
                                                        </div>
                                                    </div>                                           
                                                </div><!--end card-body-->
                                            </div><!--end card-->
   
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    Publish
                                                </div>
                                                <div class="card-body">
                                                    <div class="row m-0">
                                                        <div class="form-group w-100 mb-1">
                                                            <label for="name">Created On: </label>
                                                            {{date('d M, Y h:i A', strtotime($obj->created_at))}}
                                                        </div>
                                                        <div class="form-group w-100  mb-1">
                                                            <label for="name">Last Updated On: </label>
                                                            {{date('d M, Y h:i A', strtotime($obj->updated_at))}}
                                                        </div>
                                                        <div class="form-group w-100  mb-1">
                                                            <label for="name">Last Updated By: </label>
                                                            @if(!$obj->updated_user)
                                                                {{auth()->user()->name}}
                                                            @else
                                                                {{$obj->updated_user->name}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(auth()->user()->can($permissions['edit']))
                                                    <div class="row m-0">
                                                        <div class="form-group w-100 mb-1">
                                                            <label for="name">Status </label>
                                                            <select name="status" class="form-control webadmin-select2-input">
                                                                <option value="Open" @if($obj->status == 'Open') selected="selected" @endif>Open</option>
                                                                <option value="Close" @if($obj->status == 'Close') selected="selected" @endif>Close</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group w-100 mb-1">
                                                            <label for="name">Remarks </label>
                                                            <textarea name="remarks" class="form-control">{{$obj->remarks}}</textarea>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                @if(auth()->user()->can($permissions['edit']))
                                                <div class="card-footer text-muted">
                                                    <button class="btn btn-sm btn-primary float-right">Update</button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>    
                                    </div>
                            </form> 
                        </div><!--end col-->
                    </div><!--end row-->

                </div><!-- container -->

                @include('admin._partials.footer')
            </div>
            <!-- end page content -->
@endsection
@section('footer')

@parent
@endsection