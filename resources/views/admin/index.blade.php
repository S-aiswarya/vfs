@extends('admin._layouts.default')
@section('header')
<style>
    
    .card.report-card .card-body {
        transition: .25s;
        
    }
    .card.report-card .card-body:hover{
        transition: .25s;
        background-color: #fff5eb;
    }
    
    
    .card.sale-card{
        border: 1px solid #f99d45;
    }

   .card a {
        color: #236342 !important;
        text-decoration: none !important;
        text-transform: uppercase;
    }
    
    .card a:hover {
        color: #f99d45 !important;
        text-decoration: underline!important;
    }

    
    
</style>
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
                        <!-- <li class="creat-btn">
                            <div class="nav-link">
                                <a class=" btn btn-sm btn-soft-primary" href="#" role="button"><i class="fas fa-plus mr-2"></i>New Task</a>
                            </div>                                
                        </li> -->                           
                    </ul>
                </nav>
                <!-- end navbar-->
            </div>
            <!-- Top Bar End -->

            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Analytics</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div><!--end col-->                                    
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div id="leads-chart"></div>
                        </div>
                        <div class="col-md-6">
                            <div id="students-chart"></div>
                        </div>
                    </div>

                </div><!-- container -->

                @include('admin._partials.footer')
            </div>
            <!-- end page content -->
@endsection
@section('footer')
    @parent
    <script src="{{asset('admin/assets/js/highcharts.js')}}"></script>

    <script type="text/javascript">

        var lead_markers = @json($leads);
        lead_markers = jQuery.parseJSON(lead_markers);

        var student_markers = @json($applications);
        student_markers = jQuery.parseJSON(student_markers);

        var dt = new Date();
        dt.setDate( dt.getDate() - 6 );

        Highcharts.chart('leads-chart', {
            title: {
                text: 'Leads received for the last 7 days'
            },

            yAxis: {
                title: {
                    text: 'Leads'
                },
                allowDecimals: false,
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            xAxis: {
                type: 'datetime'
            },
            plotOptions: {
                series: {
                    pointStart: Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate()),
                    pointInterval: 24 * 3600 * 1000 // one day
                }
            },

            series: [{
                name: 'Leads',
                data: lead_markers
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });

        Highcharts.chart('students-chart', {
            
            title: {
                text: 'Travelers created for the last 7 days'
            },

            yAxis: {
                title: {
                    text: 'Travelers'
                },
                allowDecimals: false,
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            xAxis: {
                type: 'datetime'
            },
            plotOptions: {
                series: {
                    pointStart: Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate()),
                    pointInterval: 24 * 3600 * 1000 // one day
                }
            },

            series: [{
                name: 'Travelers',
                data: student_markers
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    </script>
@endsection