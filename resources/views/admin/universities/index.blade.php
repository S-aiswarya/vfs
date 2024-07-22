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
                                        <h4 class="page-title">All Universities</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Universities</li>
                                        </ol>
                                    </div><!--end col-->
                                    @if(auth()->user()->can($permissions['create']))
                                     <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-primary webadmin-open-ajax-popup" title="Create University" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
                                    </div>
                                    @endif
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    @include('admin._partials.search_settings', ['search_settings'=>$search_settings])
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
                                            <th class="table-width-10">Logo</th>
                                            <th class="table-width-120">Country</th>
                                            <th class="table-width-120">Name</th>
                                            <th class="table-width-10">Status</th>
                                            <th class="table-width-120">Last Updated On</th>
                                            <th class="nosort nosearch table-width-10">Emails</th>
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
            {data: 'logo', name: 'logo'},
            {data: 'country', name: 'countries.name'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'date', name: 'updated_at'},
            {data: 'action_emails', name: 'action_emails'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'},
            {data: 'action_delete', name: 'action_delete'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

        var adminValidate = function(){
            $('#InputFrm').validate({
                ignore: [],
                rules: {
                    "name": "required",
                    "country_id": "required",
                },
                messages: {
                    "name": "University name cannot be blank",
                    "country_id": "Country cannot be blank",
                },
            });
        };

        $(function(){
            $(document).on('click', '#add-new-email', function(){
                let html = '<div class="row list-item"><div class="form-group col-4 p-0"><label for="name">Email Address</label><input type="text" class="form-control" name="email[]"></div><div class="form-group col-4 pr-0"><label for="name">Phone Number</label><input type="text" class="form-control" name="phone_number[]"></div><div class="form-group col-3"><label for="name">Label</label><input type="text" class="form-control" name="label[]"></div><div class="form-group col-1"><a href="javascript:void(0);" class="btn btn-danger mt-4 remove-row">X</a></div></div>';
                $(html).insertBefore('.bottom-btn');
            })

            $(document).on('click', '.remove-row', function(){
                $(this).parents('.list-item').remove();
            })
        })
    </script>
    @parent
@endsection