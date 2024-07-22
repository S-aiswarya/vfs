@extends('admin._layouts.default')
@section('header')
    <link href="{{asset('admin/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
@endsection
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
                                        <h4 class="page-title">All Intakes</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">All Intakes</li>
                                        </ol>
                                    </div><!--end col-->
                                    @if(auth()->user()->can($permissions['create']))
                                     <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-primary webadmin-open-ajax-popup" title="Create University Intake" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
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
                                            <th class="table-width-120">Month</th>
                                            <th class="table-width-120">Year</th>
                                            <th class="table-width-10">Status</th>
                                            <th class="table-width-10">Default</th>
                                            <th class="table-width-120">Last Updated On</th>
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
    <script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script>
        var my_columns = [
            {data: 'updated_at', name: 'updated_at'},
            {data: null, name: 'id'},
            {data: 'month', name: 'month'},
            {data: 'year', name: 'year'},
            {data: 'status', name: 'status'},
            {data: 'is_default', name: 'is_default'},
            {data: 'date', name: 'updated_at'},
            {data: 'action_ajax_edit', name: 'action_ajax_edit'},
            {data: 'action_delete', name: 'action_delete'}
        ];
        var slno_i = 0;
        var order = [0, 'desc'];

        var adminValidate = function(){
            $('#InputFrm').validate({
                ignore: [],
                rules: {
                    "month": "required",
                    "year": "required",
                },
                messages: {
                    "month": "Month cannot be blank",
                    "year": "Year cannot be blank",
                },
            });
        };

        $(function(){
            $(document).on('change', '#university_id', function(){
                var university_id = $(this).val();
                var intake_id = $('#inputId').val();

                $.get(`${base_url}/intakes/courses/${university_id}/${intake_id}`, function(response){
                    $('#course-holder').html(response);
                })
            });

            $(document).on('change', '#checkedAll', function(){
                if(this.checked){
                  $(".checkSingle").each(function(){
                    this.checked=true;
                  })
                  $('#check-label').text('Deselect All');            
                }else{
                  $(".checkSingle").each(function(){
                    this.checked=false;
                  })
                  $('#check-label').text('Select All');             
                }
              });

              $(document).on('click', '.checkSingle', function(){
                if ($(this).is(":checked")){
                  var isAllChecked = 0;
                  $(".checkSingle").each(function(){
                    if(!this.checked)
                       isAllChecked = 1;
                  })              
                  if(isAllChecked == 0){ 
                    $("#checkedAll").prop("checked", true);
                    $('#check-label').text('Deselect All');
                  }     
                }else {
                  $("#checkedAll").prop("checked", false);
                  $('#check-label').text('Select All');
                }
              });
        })
    </script>
    @parent
@endsection