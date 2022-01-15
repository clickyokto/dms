<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title>{{getConfigArrayValueByKey('COMPANY_DETAILS','company_name')}}</title>

    <link rel="shortcut icon" href="{{asset('/theme_assets/img/pramix_logo.png')}}">

    {{--CSS--}}
    <link href="{{asset('/plugins/intlTelInput/intlTelInput.css')}}" rel="stylesheet"/>
    <link href="{{asset('/plugins/wysiwyg-editor/editor.css')}}" rel="stylesheet"/>


    <!-- App css -->
    <link href="{{asset('/theme_assets/css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/theme_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />


    <link href="{{asset('/pramix/css/custom.css?v=4')}}" rel="stylesheet" />
    <link href="{{asset('/theme_assets/css/jquery_confirm/jquery-confirm.css')}}" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/v/dt/dt-1.10.18/r-2.2.2/datatables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    @yield('include_css')

</head>
<body class="light_theme dark-sidenav enlarge-menu">

<!-- Header starts -->
@include('header.header')
<!-- Header ends -->

<!-- Container fluid Starts -->
<div class="container-fluid">

    <!-- Navbar starts -->
{{--@include('header.menu')--}}
    <!-- Navbar ends -->
    {{--<div class=" progress-sm" id="animated_bar">--}}
        {{--<div class="progress-bar progress-bar-striped active" role="progressbar"--}}
             {{--aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">--}}
        {{--</div>--}}
    {{--</div>--}}

    <!-- Dashboard wrapper starts -->
    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content-tab">

            <div class="container-fluid">
@yield('content')
<!-- Footer Start -->
    <footer>
        © Copyright {{\Carbon\Carbon::now()->year}}  . All Rights Reserved.
    </footer>
    <!-- Footer end -->
            </div>
        </div>
    </div>
    <!-- Dashboard Wrapper End -->

</div>
<!-- Container fluid ends -->




<script src="{{asset('/theme_assets/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    var BASE = "{{url('/')}}/";
</script>
<script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/kt-2.3.2/sc-1.4.4/datatables.min.js"></script>

<script src="{{asset('/plugins/custom.js?v=2')}}"></script>
<script src="{{asset('/plugins/intlTelInput/intlTelInput.min.js')}}"></script>
<script src="{{asset('/plugins/bootstrap_notify_master/bootstrap_notify.min.js') }}"></script>

<script src="{{asset('/theme_assets/js/custom.js?v=2')}}"></script>

<script src="{{asset('/theme_assets/js/wysiwyg-editor/editor.js') }}"></script>

{{--<script src="{{asset('/plugins/apexcharts/apexcharts.min.js') }}"></script>--}}



<!-- jQuery  -->
<script src="{{asset('/theme_assets/js/jquery-ui.min.js') }}"></script>
<script src="{{asset('/theme_assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('/theme_assets/js/metismenu.min.js') }}"></script>
<script src="{{asset('/theme_assets/js/waves.js') }}"></script>
<script src="{{asset('/theme_assets/js/feather.min.js') }}"></script>
<script src="{{asset('/theme_assets/js/jquery.slimscroll.min.js') }}"></script>

{{--<script src="{{asset('/theme_assets/pages/jquery.crm_dashboard.init.js') }}"></script>--}}

<!-- App js -->
<script src="{{asset('/theme_assets/js/app.js')}}"></script>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-en.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


<script type="text/javascript">
    $('.select2').select2();

    $('[data-toggle="tooltip"]').tooltip({
        html: true
    });



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        $(function ()
        {
            setTimeout("displaytime()", 1000);
        });
    function displaytime()
    {
        var dt= new Date();
        $('#system_time_display').html(dt.toLocaleTimeString());
        setTimeout("displaytime()", 1000)
    }

    $("input:text").click(function(){
        this.select();
    });

    $(':input[type="number"]').click(function(){
        this.select();
    });

</script>

@yield('include_js')

@yield('custom_script')



</body>
</html>
