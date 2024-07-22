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
                                        <h4 class="page-title">All Leads</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Leads</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-primary" title="Import Leads" href="{{route($route.'.import')}}" role="button"><i class="fas fa-plus mr-2"></i>Import Leads</a>
                                    </div>
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    @include('admin.leads._partials.search_settings', ['search_settings'=>$search_settings])
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
                                            <th class="table-width-120">Lead Id</th>
                                            <th class="table-width-120">Name</th>
                                            <th class="table-width-120">Email</th>
                                            <th class="table-width-120">Phone Number</th>
                                            <th class="table-width-120">Office</th>
                                            <th class="table-width-120">Agency</th>
                                            <th class="table-width-120">Stage</th>
                                            <th class="table-width-120">Assigned To</th>
                                            <th class="nosort nosearch table-width-10">Verified</th>
                                            <th class="nosort nosearch table-width-10">Archived</th>
                                            <th class="table-width-120">Submitted On</th>
                                            <th class="nosort nosearch table-width-10">View</th>
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
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'office', name: 'offices.name'},
            {data: 'agency', name: 'agencies.name'},
            {data: 'stage', name: 'stages.name'},
            {data: 'assigned_to', name: 'users.name'},
            {data: 'verification_status', name: 'verification_status'},
            {data: 'archive_status', name: 'archive_status'},
            {data: 'date', name: 'created_at'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'},
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

    </script>
    @parent
@endsection