
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><x-site-name></x-site-name> - Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="<x-site-name></x-site-name> - Admin" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <x-fav-icon></x-fav-icon>

        <link href="{{asset('admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="{{asset('admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" /> 
        <link href="{{asset('admin/plugins/jquery-steps/jquery.steps.css')}}" rel="stylesheet" type="text/css" media="screen" />
        <!-- App css -->
        <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/plugins/jquery-datetimepicker/css/jquery.datetimepicker.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{asset('admin/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{asset('admin/plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
        <link href="{{asset('admin/assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/assets/css/theme.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin/assets/css/bcie.css')}}" rel="stylesheet" type="text/css" />
        
        @section('header')
        @show

        <style type="text/css">
            .select2-container .select2-selection--single{
                height: 35px;
            }
            .select2-container--default .select2-selection--single, .select2-dropdown, .select2-container--default .select2-search--dropdown .select2-search__field{
                border: 1px solid #e3ebf6;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height: 35px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow{
                height: 34px;
            }

            .search-section .select2-container .select2-selection--single{
                height: 31px;
            }
            .search-section .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height: 31px;
            }
            .search-section .select2-container--default .select2-selection--single .select2-selection__arrow{
                height: 30px;
            }
            .search-section .select2-container--default .select2-selection--single, .search-section .select2-dropdown, .search-section .select2-container--default .select2-search--dropdown .select2-search__field{
                border: 1px solid #1761fd;
            }

            #sortable{
                list-style-type: none;
                padding: 0;
            }

            .sortable{
                list-style-type: none;
            }

            .ui-state-default {
                border: 1px solid #e3ebf6;
                background: #fff;
                font-weight: normal;
                color: #454545;
                line-height: 40px;
                margin-bottom: 10px;
            }

            .jstree-default .jstree-anchor {
                line-height: 30px;
                height: 30px;
            }

            .datatable-width{
                width: 100% !important;
            }

            .ck.ck-label{
                display: none !important;
            }

            .daterangepicker{
                z-index: 99999999;
            }

            .select2-container--open {
                z-index: 99999999;
            }
            .sorting_1{
                text-align: center;
            }

            .media-popup-head .nav-tabs .nav-item {
                margin-bottom: -1px;
                padding: 10px;
                border: 1px solid #e3ebf6;
                margin-right: 5px;
            }
            .media-popup-head .nav-tabs .nav-item .active{
                color: #79acf2;
                text-decoration: underline;
            }

            .item-meida img{
                width: 200px;
            }

            #webAdminModal .modal-dialog {
                position: fixed;
                margin: auto;
                width: 500px;
                height: 100%;
                right: 0px;
            }
            #webAdminModal .modal-content {
                height: 100%;
            }

            #webAdminModal .form-group{
                width: 100%;
                margin-bottom: 0;
            }

            #webAdminModal .row{
                margin: 0;
            }

            .file-upload-holder img{
                height: 150px;
            }

            .file-upload-holder{
                margin-bottom: 15px;
            }

            #webAdminModal .modal-body {
                padding: 0 10px;
                overflow: auto;
            }

            #webAdminModal .card-header{
                padding: 10px 0;
            }

            #webAdminModal .card-header h5{
                margin: 0;
            }

            #webAdminModal .card-header h5 button{
                color: #2a3853;
                font-size: 16px;
                font-weight: 500;
            }

            .select2-container--default .select2-selection--multiple{
                border: 1px solid #e3ebf6 !important;
                margin-bottom: 10px;
            }

            .select2-container .select2-selection--multiple{
                min-height: 45px;
            }

            .confirm-wrap .ck-content{
                min-height: 100px;
            }

            #webAdminModal .iti{
                width: 100%;
                margin-bottom: 10px;
            }

            .select2-container{
                margin-bottom: 10px;
            }
        </style>

    </head>

    <body>
        <!-- Left Sidenav -->
        @include('admin._partials.header')
        <!-- end left-sidenav-->
        

        <div class="page-wrapper">
            @section('content')
            @show
        </div>
        <!-- end page-wrapper -->
        <div class="modal fade" id="webAdminModal" tabindex="-1" role="dialog" aria-labelledby="webAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                </div>
            </div>
        </div>
        

        <script type="text/javascript">
            var _token = "{{csrf_token()}}";
            var base_url = "{{route('admin.auth.login')}}";
        </script>
        <!-- jQuery  -->
        <script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/metismenu.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/waves.js')}}"></script>
        <script src="{{asset('admin/assets/js/feather.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/simplebar.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/moment.js')}}"></script>
        <script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('admin/plugins/jquery-datetimepicker/js/jquery.datetimepicker.full.min.js')}}"></script>

        <script type="text/javascript" src="{{asset('admin/plugins/select2/select2.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('admin/plugins/sweet-alert2/sweetalert2.min.js')}}"></script>

        <script src="{{asset('admin/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin/plugins/jquery-confirm/jquery-confirm.min.js')}}" type="text/javascript"></script>

        <script src="{{asset('admin/plugins/apex-charts/apexcharts.min.js')}}"></script>
        <script src="{{asset('admin/assets/pages/jquery.analytics_dashboard.init.js')}}"></script>

        <!-- Required datatable js -->
        <script src="{{asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

        <!-- Responsive examples -->
        <script src="{{asset('admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

        <script src="{{asset('admin/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
        <script src="{{ asset('admin/plugins/ckeditor/build/ckeditor.js')}}"></script>

        @section('footer')
        @show
        <!-- App js -->
        <script src="{{asset('admin/assets/js/app.js')}}"></script>
        <script src="{{asset('admin/assets/js/webadmin.js')}}"></script>
        <script src="{{asset('admin/assets/js/webadmin.v2.js')}}"></script>
        

        <script type="text/javascript">
            $(function(){

                $("#InputFrmSteps").steps({
                    headerTag: "h3",
                    bodyTag: "fieldset",
                    transitionEffect: "slide",
                    enableAllSteps: "true",
                    enablePagination: false,
                });
            })
        </script>
        
        <script>
            if($('#datatable').length)
            {
                initDt(); 
            }

            if($('#datatable2').length)
            {
                var $table = $('#datatable2');
                var ajaxUrl = $table.data('datatable-ajax-url');
                console.log(ajaxUrl)
                //var order = '';
                var dt_table2 = $table.DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,
                    "processing": true,
                    "serverSide": true,
                    responsive: true,
                    ajax: {
                        url: ajaxUrl,
                        data: function(d) {
                            var advanced_search = {};
                            $('.datatable-advanced-search').each(function(i, obj) {
                                    advanced_search[$(this).attr('name')] = $(this).val();
                            });
                            d.data = advanced_search;
                        }
                    },
                    columns: my_columns2,
                    //"stateSave": true,
                    'aoColumnDefs': [
                        { 'bSortable': false, 'sClass': "text-center table-width-10", 'aTargets': ['nosort'] },
                        { "bSearchable": false, "aTargets": [ 'nosearch' ] },
                        { "bVisible": false, 'sClass': "d-none", "aTargets": ['nodisplay'] }
                    ],
                    errMode: 'throw',
                    "order": [order2],
                    "language": {
                        "search": "",
                        'searchPlaceholder': 'Search...'
                    },
                    initComplete: function(settings, json) {
                        $(this).trigger('initComplete', [this]);
                        $(window).trigger('resize');
                        this.api().columns().every( function () {

                        });
                    },
                    fnRowCallback : function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                        updateDtSlno(this, slno_i2);
                    }
                });
   

                $('#datatable2 #column-search tr th').each( function () {
                    var title = $(this).text();
                    var columnClass = $(this).attr('class');
                    if($(this).hasClass('searchable-input')){
                        if($(this).hasClass('date'))
                        {
                            var id = $(this).attr('data-id');
                            $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control input-sm daterange" name="updated_at" id="'+id+'" />' );
                            $('.daterange').daterangepicker({
                                timePicker: true,
                                autoUpdateInput: false,
                                drops: "up",
                                locale: {
                                    cancelLabel: 'Clear',
                                    format: 'MM/DD/YYYY HH:mm'
                                }
                            });
                        }
                        else if($(this).hasClass('date_time'))
                        {
                            var id = $(this).attr('data-id');
                            $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control input-sm daterange" name="date_time" id="'+id+'" />' );
                            $('#'+id).daterangepicker({
                                timePicker: true,
                                autoUpdateInput: false,
                                drops: "up",
                                locale: {
                                    cancelLabel: 'Clear',
                                    format: 'MM/DD/YYYY HH:mm'
                                }
                            });
                        }
                        else
                            $(this).html(  '<input type="text" placeholder="Search '+title+'" class="form-control input-sm search-input" />' );
                    }
                });

                $( '#datatable2 thead').on( 'keyup change', ".search-input",function () {
       
                    dt_table2
                        .column( $(this).parent().index()+1)
                        .search( this.value )
                        .draw();
                });

                $( '#datatable2 thead').on( 'change', ".searchable-option",function () {
                    dt_table2
                        .column( $(this).parent().index()+1)
                        .search( this.value )
                        .draw();
                });

            }

            $(function(){
                $(".copy-name").keyup(function () {
                    var name = $(this).val();
                    $("input[name='slug']").val(slugify(name));
                    $("input[name='title']").val(name);
                    $("input[name='browser_title']").val(name);
                });

                $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                    if($('#datatable').length)
                    {
                        dt_table
                           .columns.adjust()
                           .responsive.recalc();
                    }

                    if($('#datatable2').length)
                    {
                        dt_table2
                           .columns.adjust()
                           .responsive.recalc();
                    }
                });

                $(document).on('click', '#search-table-clear-btn', function(){
                    $('#searchForm').find("input[type=text]").val("");
                    $('#searchForm').find("select").select2("val", 0);
                    dt();
                })
            });

            function initDt()
            {
                var $table = $('#datatable');
                var ajaxUrl = $table.data('datatable-ajax-url');
                console.log(ajaxUrl)
                //var order = '';
                var dt_table = $table.DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,
                    "processing": true,
                    "serverSide": true,
                    responsive: true,
                    ajax: {
                        url: ajaxUrl,
                        data: function(d) {
                            var advanced_search = {};
                            $('.datatable-advanced-search').each(function(i, obj) {
                                advanced_search[$(this).attr('name')] = $(this).val();
                            });
                            d.data = advanced_search;
                        }
                    },
                    columns: my_columns,
                    "stateSave": true,
                    'aoColumnDefs': [
                        { 'bSortable': false, 'sClass': "text-center table-width-10", 'aTargets': ['nosort'] },
                        { "bSearchable": false, "aTargets": [ 'nosearch' ] },
                        { "bVisible": false, 'sClass': "d-none", "aTargets": ['nodisplay'] }
                    ],
                    errMode: 'throw',
                    "order": [order],
                    "language": {
                        "search": "",
                        'searchPlaceholder': 'Search...'
                    },
                    initComplete: function(settings, json) {
                        $(this).trigger('initComplete', [this]);
                        $(window).trigger('resize');
                        this.api().columns().every( function () {

                        });
                    },
                    fnRowCallback : function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                        updateDtSlno(this, slno_i);
                    }
                });

                $('#datatable #column-search tr th').each( function () {
                    var title = $(this).text();
                    var columnClass = $(this).attr('class');
                    if($(this).hasClass('searchable-input')){
                        if($(this).hasClass('date'))
                        {
                            var id = $(this).attr('data-id');
                            $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control input-sm daterange" name="updated_at" id="'+id+'" />' );
                            $('.daterange').daterangepicker({
                                timePicker: true,
                                autoUpdateInput: false,
                                drops: "up",
                                locale: {
                                    cancelLabel: 'Clear',
                                    format: 'MM/DD/YYYY HH:mm'
                                }
                            });
                        }
                        else if($(this).hasClass('date_time'))
                        {
                            var id = $(this).attr('data-id');
                            $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control input-sm daterange" name="date_time" id="'+id+'" />' );
                            $('#'+id).daterangepicker({
                                timePicker: true,
                                autoUpdateInput: false,
                                drops: "up",
                                locale: {
                                    cancelLabel: 'Clear',
                                    format: 'MM/DD/YYYY HH:mm'
                                }
                            });
                        }
                        else
                            $(this).html(  '<input type="text" placeholder="Search '+title+'" class="form-control input-sm search-input" />' );
                    }
                });

                $( '#datatable thead').on( 'keyup change', ".search-input",function () {
       
                    dt_table
                        .column( $(this).parent().index()+1)
                        .search( this.value )
                        .draw();
                });

                $( '#datatable thead').on( 'change', ".searchable-option",function () {
                    dt_table
                        .column( $(this).parent().index()+1)
                        .search( this.value )
                        .draw();
                });

                $( '#datatable').on( 'dblclick', "td.edittable",function () {
                    newInput(this);
                });

            }

            function closeInput(elm) {
                var value = $(elm).find('input').val();
                $(elm).empty().text(value);

                $(elm).bind("dblclick", function () {
                    newInput(elm);
                });
            }

            function newInput(elm) {
                $(elm).unbind('dblclick');

                var value = $(elm).text();
                $(elm).empty();

                $("<input>")
                    .attr('type', 'text')
                    .attr('class', 'form-control')
                    .val(value)
                    .blur(function () {
                        closeInput(elm);
                    })
                    .appendTo($(elm))
                    .focus();
            }

            function updateDtSlno(dt, slno_i) {
                if (typeof dt != "undefined") {
                    if(typeof slno_i == 'undefined')
                        slno_i = 0;
                    table_rows = dt.fnGetNodes();
                    var oSettings = dt.fnSettings();
                    $.each(table_rows, function(index){
                        $("td:eq(" + slno_i + ")", this).html(oSettings._iDisplayStart+index+1);
                    });
                }
            }

            function dt(){
                if($('#datatable').length)
                {
                    if($('.viewfile').length && $('#checkin-register-type-id').length){
                        //alert($('#checkin-register-type-id').val());
                        if($('#checkin-register-type-id').val() != "" && $('#checkin-register-type-id').val() != null){
                            $('.viewfile').removeClass('d-none');
                        }
                        else{
                            $('.viewfile').addClass('d-none');
                        }
                    }
                    $('#datatable').DataTable().clear().destroy();
                    initDt();


                }
                if($('#datatable2').length)
                    dt_table2.ajax.reload();
            }
            
            var Youtube = (function () {
                'use strict';

                var video, results;

                var getThumb = function (url, size) {
                    if (url === null) {
                        return '';
                    }
                    size    = (size === null) ? 'big' : size;
                    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
                    var match = url.match(regExp);
                    video   = (match&&match[7].length==11)? match[7] : false;

                    if (size === 'small') {
                        return 'http://img.youtube.com/vi/' + video + '/2.jpg';
                    }
                    return 'http://img.youtube.com/vi/' + video + '/0.jpg';
                };

                return {
                    thumb: getThumb
                };
            }());

            var thumb = Youtube.thumb('https://youtu.be/TWhxDvVvGBs', 'big');

console.log(thumb);

        </script>
    </body>

</html>