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
                                        
                                        <h4 class="page-title">Create new Permissions</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                                            <li class="breadcrumb-item"><a href="{{route('admin.permissions.index')}}">All Permissions</a></li>
                                            <li class="breadcrumb-item active">Add new Permissions</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                      <a class=" btn btn-sm btn-primary" href="{{route($route.'.create')}}" role="button"><i class="fas fa-plus mr-2"></i>Create New</a>
                                    </div>
                                    
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                    
                    <div class="row">
                        <div class="col-lg-12">
                            @include('admin._partials.notifications')
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="SettingsFrm" data-validate=true>
                                    @csrf
                                    <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
                                        <div class="settings-item row">
                                            <div class="form-group col-md-3">
                                                <label for="name">Guard</label>
                                                <select class="form-control" name="guard_name[]">
                                                  <option value="admin">Admin</option>
                                                  <option value="user">User</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name[]" id="name_1">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="name">Route</label>
                                                <input type="text" class="form-control" name="route[]" id="route_1" >
                                            </div>
                                            <div class="col-md-1 center-block">
                                                
                                            </div>
                                          </div>
                                          <div class="row bottom-btn">
                                            <div class="col-md-12" align="right">
                                                <a href="javascript:void(0);" class="btn btn-success btn-addnew">Add New</a>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
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
    <script>
      var idInc = 2;
      $(document).ready(function(){

        $(document).on('click', '.btn-addnew', function(){
          var html ='<div class="settings-item row"><div class="form-group col-md-3"><label for="name">Guard</label><select class="form-control" name="guard_name[]" id="guard_name_'+idInc+'"><option value="admin">Admin</option><option value="user">User</option></select></div><div class="form-group col-md-4"><label for="name">Name</label><input type="text" class="form-control" name="name[]" id="name_'+idInc+'" ></div><div class="form-group col-md-4"><label for="name">Route</label><input type="text" class="form-control" name="route[]" id="route_'+idInc+'" ></div><div class="col-md-1 center-block"><div class="form-group"><a href="javascript:void(0);" class="btn btn-danger mt-4 remove-row">X</a></div></div></div>';
          $(html).insertBefore('.bottom-btn');
          $('.input_type').select2();
          idInc++;
        });

        $(document).on('click', '.remove-row', function(){
          $(this).parents('.settings-item').remove();
        })

        $.extend( $.validator.prototype, {
          checkForm: function () {
            this.prepareForm();
            for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
              if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                this.check(this.findByName(elements[i].name)[cnt]);
                }
              } else {
                this.check(elements[i]);
              }
            }
            return this.valid();
          },
          showErrors: function( errors ) {
          if ( errors ) {
            var validator = this;

            // Add items to error list and map
            $.extend( this.errorMap, errors );
            this.errorList = $.map( this.errorMap, function( message, name ) {
              return {
                message: message,
                element: validator.findById(name)[0]
              };
            });

            // Remove items from success list
            this.successList = $.grep( this.successList, function( element ) {
              return !( element.name in errors );
            } );
          }
          if ( this.settings.showErrors ) {
            this.settings.showErrors.call( this, this.errorMap, this.errorList );
          } else {
            this.defaultShowErrors();
          }
        },
        findById: function( id ) {
          // select by name and filter by form for performance over form.find(“[id=…]”)
          var form = this.currentForm;
          return $(document.getElementById(id)).map(function(index, element) {
          return element.form == form && element.id == id && element || null;
          });
        },
      });

       var validator = $('#SettingsFrm').validate({
          rules: {
            "name[]": "required",
            "route[]": "required",
          },
          messages: {
            "name[]": "Name cannot be blank",
            "route[]": "Route cannot be blank",
          },
        });
      });
    </script>
@parent
@endsection