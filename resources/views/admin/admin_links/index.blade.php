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
                                        
                                        <h4 class="page-title">Admin Links</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item active">Admin Links</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                      <a class=" btn btn-sm btn-primary" href="{{route($route.'.index')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
                                    </div>
                                    
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    
                    <div class="row">
                        <div class="col-lg-6">
                            @include('admin._partials.notifications')
                            <div class="card">
                                <div class="card-body">
                                    @include('admin.admin_links.form', ['obj'=>$obj])                                                                 
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" class="p-t-15" id="orderForm" data-validate=true>
                                    @csrf
                                    <div class="row m-0">
                                        <div class="col-md-12">
                                          @if(count($links)>0)
                                            <ul id="sortable" class="sortable">
                                              @foreach($links as $link)
                                              <li class="ui-state-default pr-2 pl-2" data-order-id="{{$link->id}}">
                                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{$link->name}}
                                                <div class="float-right">
                                                  <a href="{{route('admin.admin-links.index', [encrypt($link->id)])}}" class="mr-1"><i class="fa fa-pencil-alt"></i></a>
                                                  @if(count($link->children) == 0)
                                                    <a href="{{route('admin.admin-links.destroy', [encrypt($link->id)])}}" class="webadmin-btn-warning-popup" data-message="Are you sure to delete?  Associated data will be removed if it is deleted." data-redirect-url="{{route($route.'.index')}}"><i class="fa fa-trash"></i></a>
                                                  @endif
                                                </div>
                                                @if(count($link->children))
                                                  <ul class="sortable">
                                                    @include('admin.admin_links.children',['links' => $link->children, 'depth'=>0])
                                                  </ul>
                                                @endif
                                              </li>
                                              @endforeach
                                            </ul>
                                          @else
                                          <p class="text-center">No Admin links added!</p>
                                          @endif
                                        </div>
                                      </div>
                                      @if(count($links)>0)
                                        <div class="row m-0">
                                          <div class="col-md-12 text-right">
                                            <button class="btn btn-primary" id="order-submit-btn" type="button">Save Order</button>
                                          </div>
                                        </div>
                                      @endif
                                    </form>                                                                   
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->

                </div><!-- container -->

                @include('admin._partials.footer')
            </div>
            <!-- end page content -->
@endsection
@section('footer')
    <script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
      $( function() {

        $('.sortable').each(function(index){
          $(this).sortable();
        })
        //$( "#sortable" ).sortable();
      });

      $(function(){
            $(document).on('click', '#order-submit-btn', function(){
                var obj = $(this);
                obj.prop('disabled', true).text('Processing...');
                var order="";
                $('.sortable').each(function(index){
                  $(this).find('li').each(function(i) {
                    if (order=='')
                        order = $(this).attr('data-order-id');
                    else
                        order += "," + $(this).attr('data-order-id');
                  });
                })

                var data = $('#orderForm').serialize()+"&order="+order;

                $.post("{{route('admin.admin-links.order-store')}}", data, function(data){
                  $.alert('New menu order successfully saved!');
                  location.reload(true);
                })
            })
        })

       var validator = $('#InputFrm').validate({
          rules: {
            "name": "required",
            "permissions_id": "required",
          },
          messages: {
            "name": "Name cannot be blank",
            "permissions_id": "Route cannot be blank",
          },
        });

    </script>
@parent
@endsection