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
                                        <h4 class="page-title">All Users</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Users</li>
                                        </ol>
                                    </div><!--end col-->
                                    @if(auth()->user()->can($permissions['create']))
                                     <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-primary webadmin-open-ajax-popup" title="Create User" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
                                    </div>
                                    @endif
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    @include('admin.users._partials.search_settings', ['search_settings'=>$search_settings])
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover demo-table-search table-responsive-block" id="datatable"
                                           data-datatable-ajax-url="{{ route($route.'.index') }}" >
                                        <thead id="column-search">
                                        <tr>
                                            <th class="nodisplay"></th>
                                            <th class="table-width-10">ID</th>
                                            <th class="table-width-120">Role</th>
                                            <th class="table-width-120">Name</th>
                                            <th class="table-width-120">Email</th>
                                            <th class="table-width-120">Phone</th>
                                            <th class="table-width-120">Country</th>
                                            <th class="nosort nosearch table-width-120">Offices</th>
                                            <th class="nosort nosearch table-width-10">Status</th>
                                            <th class="nosort nosearch table-width-10">@if(auth()->user()->can($permissions['edit'])) Targets @endif</th>
                                            <th class="nosort nosearch table-width-10">@if(auth()->user()->can($permissions['edit'])) Edit @else View @endif</th>
                                            <th class="nosort nosearch table-width-10">Delete</th>
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
            {data: 'role_name', name: 'roles.name'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'office_country', name: 'office_countries.name'},
            {data: 'offices', name: 'offices'},
            {data: 'status', name: 'status'},
            {data: 'action_targets', name: 'action_targets'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'},
            {data: 'action_delete', name: 'action_delete'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

        var adminValidate = function(){
            $('#InputFrm').validate({
                ignore: [],
                rules: {
                    "role_id": "required",
                    "name": "required",
                    "phone_number": "required",
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: function(element){
                            return $("#inputId").val() =="";
                        }
                    },
                    confirm_password: {
                        equalTo: "#password",
                    },
                },
                messages: {
                    "role_id": "User role cannot be blank",
                    "name": "User name cannot be blank",
                    "phone_number": "Phone number cannot be blank",
                    email: {
                        required: "Email address cannot be blank",
                    },
                    password: "Password cannot be blank",
                },
            });
        };

        var targetValidate = function(){
            $('#TargetFrm').validate({
                ignore: [],
                rules: {
                    "intake_id": "required",
                    "application_submitted": "required",
                    "unconditional_offers": "required",
                    "deposit_paid": "required",
                    "visa_obtained": "required",
                },
                messages: {
                    "intake_id": "Intake cannot be null",
                    "application_submitted": "Application submitted target cannot be null",
                    "unconditional_offers": "Conditional offers target cannot be null",
                    "deposit_paid": "Deposit paid target cannot be null",
                    "visa_obtained": "Visa obtained target cannot be null",
                },
            });
        }

        var statusValidate = function(){
            $('#StatusFrm').validate({
                ignore: [],
                rules: {
                    "user_id": "required"
                },
                messages: {
                    "user_id": "Select a user to handover the responsibilities"
                },
            });
        }

        $(function(){
            $(document).on('change', '#role_id', function(){
                if($(this).val() == 5)
                    $('#manager-div').show();
                else
                    $('#manager-div').hide();
            });

            $(document).on('change', '#office_id', function(){
                if($('#manage_id').length){
                    
                }
            })

            $(document).on('change', '#intake_id', function(){
                let url = $(this).find(':selected').data('url');
                $('#target_sec').html('<p class="col-12 text-center">Fetching...</p>');
                $.get(url, function(response){
                    $('.confirm-wrap').replaceWith(response);
                })
            })
        })
    </script>
    @parent
@endsection