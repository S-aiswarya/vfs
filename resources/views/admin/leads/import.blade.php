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
                                        <h4 class="page-title">Import Leads</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item"><a href="{{route('admin.leads.index')}}">Leads</a></li>
                                            <li class="breadcrumb-item active">Imports Leads</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                        <a class=" btn btn-sm btn-warning" title="Download Sample Excel" href="{{asset('lead_upload.xlsx')}}" target="_blank" ><i class="fas fa-download"></i> Download Sample Excel</a>
                                    </div>
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    @include('admin._partials.notifications')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route($route.'.import-save') }}" class="p-t-15 row" id="InputFrm" enctype="multipart/form-data" data-validate=true>
                                            @csrf
                                            <div class="form-group m-3">
                                            <label for="exampleFormControlFile1">Import File</label>
                                            <input type="file" class="form-control-file" id="file" name="file">
                                            </div>
                                            <div class="form-group m-3 row">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                            </div>
                                    </form>
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
<script type="text/javascript">
   
</script>
@endsection