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
                                        <h4 class="page-title">Register Types</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Registers</li>
                                        </ol>
                                    </div><!--end col-->
                                    @if(auth()->user()->can($permissions['create']))
                                     <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-primary webadmin-open-ajax-popup" title="Create register" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
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
                                            <th class="table-width-120">Register Name</th>
                                            <th class="table-width-120">Group name</th>
                                            <th class="table-width-120">key Type</th>
                                            <th class="table-width-120">Check Out</th>
                                            <th class="table-width-120">Sort Order</th>
                                            <th class="table-width-120">Last Updated On</th>
                                            <th class="nosort nosearch table-width-10">@if(auth()->user()->can($permissions['edit'])) Edit @else View @endif</th>
                                           
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
            {data: 'register_name', name: 'register_name'},
            {data: 'group_name', name: 'group_name'},
            {data: 'key_types', name: 'key_types.key_name'},
            {data: 'check_out', name: 'check_out'},
            {data: 'sort_order', name: 'sort_order'},
            {data: 'date', name: 'updated_at'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

        var adminValidate = function(){
            $('#InputFrm').validate({
                ignore: [],
                rules: {
                    "register_name": "required",
                   
                },
                messages: {
                    "register_name": "name cannot be blank",
                },
            });
        };
    </script>
    @parent
@endsection