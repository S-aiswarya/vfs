@extends('admin._layouts.default')

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
                                        <h4 class="page-title">All Check-Ins</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Check-Ins</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                    <a class=" btn btn-sm btn-primary d-none viewfile" id="view-excel-file" >View Reports</a> 
                                    </div>

                                    @if(auth()->user()->can($permissions['create']))
                                     <div class="col-auto align-self-center">
                                        <a class="btn btn-success d-none viewfile" id="export-to-excel"><i class="fas fa-download"></i> Export to Excel</a>
                                        <!-- <a class=" btn btn-sm btn-primary webadmin-open-ajax-popup" title="CheckIns" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>  -->
                                     </div>
                                    @endif
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                        
                      

                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    
                    @include('admin.check_ins.partials.search_settings')
                  
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card all_check_ins">
                                <div class="card-body">
                                    <table class="table table-hover demo-table-search table-responsive-block" id="datatable"
                                           data-datatable-ajax-url="{{ route($route.'.index') }}" >
                                        <thead id="column-search">
                                        <tr>
                                            <th class="nodisplay"></th>
                                            <th class="table-width-10">ID</th>
                                            <th style="width: 100px;">Type</th>
                                            <th class="table-width-120">Name</th>
                                            <th class="table-width-120">Phonenumber</th>
                                            <th class="table-width-120">Token</th>
                                            <th class="table-width-120">Check-In Time</th>
                                            <th class="table-width-120">Check-Out Time</th>
                                            <th class="table-width-120">Last Updated On</th>
                                            <th class="nosort nosearch table-width-10">@if(auth()->user()->can($permissions['edit'])) View @else View @endif</th>
                                            <!-- <th class="nosort nosearch table-width-10">Delete</th>  -->
                                        </tr>



                                        </thead>

                                        <tbody>

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 

                </div><!-- container -->

                @include('admin._partials.footer')
            </div>
            <!-- end page content -->
@endsection
@section('footer')
    <script>
        var my_columns = [
            {data: 'updated_at', name: 'updated_at'},
            {data: null, name: 'id'},
            {data: 'checkin_type', name:'register_types.register_name'},
            {data: 'name', name: 'name'},
            {data: 'phonenumber', name: 'phonenumber'},
            {data: 'token', name: 'token'},
            {data: 'entry_time', name: 'entry_time'},
            {data: 'exit_time', name: 'exit_time'},
            {data: 'date', name: 'updated_at'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'},
            // {data: 'action_delete', name: 'action_delete'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

        var adminValidate = function(){
            $('#InputFrm').validate({
                ignore: [],
                rules: {
                    "name": "required",             
                    "email":{
                      required:false,
                      email:true,
                    },
                   // "check_in_Type_id":"required",
                    "entry_time":"required",
                    "exit_time":"required",
                    
                  },
            });
        };
 
        $(function(){
            $('#export-to-excel').addClass('d-none');
            $(document).on('click', '#export-to-excel', function(){
                var form_action = "{{route('admin.checkins.export')}}"
                $('#searchForm').attr('action', form_action);
                $('#searchForm').submit();
            })
           
        })
         
    

        $(function(){
            $('#view-excel-file').addClass('d-none');
            $(document).on('click', '#view-excel-file', function(){
                var form_action = "{{route('admin.checkins.viewexport')}}"
                $('#searchForm').attr('action', form_action);
                $('#searchForm').submit();
            })
           
        })
    </script>
    
    @parent

@endsection