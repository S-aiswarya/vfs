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
                                        <h4 class="page-title">All Login Details</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Login Details</li>
                                        </ol>
                                    </div><!--end col-->
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover demo-table-search table-responsive-block" id="datatable"
                                           data-datatable-ajax-url="{{ route($route.'.index') }}" >
                                        <thead id="column-search">
                                        <tr>
                                            <th class="nodisplay"></th>
                                            <th class="table-width-10 text-center">ID</th>
                                            <th class="table-width-120">User</th>
                                            <th class="table-width-120">Action</th>
                                            <th class="table-width-120">Action_time</th>
                                            <th class="table-width-120">Ip Address</th>
                                            <!-- <th class="table-width-120">Logged In At</th> -->
                                            <th class="nosort nosearch table-width-10 text-center">Delete</th>
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
           // {data: 'created_at', name: 'created_at'},
            {data: null, name: 'id'},
            {data: 'user', name: 'users.name'},
            {data: 'action', name: 'action'},
            {data: 'action_time', name: 'action_time'},
            {data: 'ip_address', name: 'ip_address'},
            // {data: 'date', name: 'date'},
            // {data: 'action_delete', name: 'action_delete'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];
    </script>
    @parent
@endsection