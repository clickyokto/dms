<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <link rel="shortcut icon" href="{{asset('/theme_assets/img/pramix_logo.png')}}">

    {{--CSS--}}
    <link href="{{asset('/plugins/intlTelInput/intlTelInput.css')}}" rel="stylesheet"/>

    <link href="{{asset('/theme_assets/css/bootstrap.min.css')}}" media="screen" rel="stylesheet"/>
    <link href="{{asset('/theme_assets/css/jquery_confirm/jquery-confirm.css')}}" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.css"
          rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.css"
          rel="stylesheet"/>
    <link href="{{asset('/pramix/css/customer_invoice.css')}}" media="screen" rel="stylesheet"/>

    @yield('include_css')

</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">PRAMIX IT SOLUTIONS</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Home</a></li>
                <li><a href="#">Who We Are</a></li>
                <li><a href="#">Our Services</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Career</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Navbar starts -->
    <!-- Navbar ends -->

    <!-- Dashboard wrapper starts -->
@yield('content')
<!-- Dashboard Wrapper End -->

</div>
<!-- Container fluid ends -->


<!-- Footer Start -->
<footer id="footer" class="clearfix">

    <div id="sub-floor">
        <div class="container">
            <div class="row">
                <div class="col-md-4 copyright">
                    | Copyright © 2019 PRAMIX IT
                </div>

            </div> <!-- end .row -->
        </div>
    </div>

</footer>
<!-- Footer end -->

<script src="{{asset('/theme_assets/js/jquery.js')}}"></script>

<script type="text/javascript">
    var BASE = "{{url('/')}}/";
</script>


<script src="{{asset('/plugins/intlTelInput/intlTelInput.min.js')}}"></script>
<script src="{{asset('/plugins/bootstrap_notify_master/bootstrap_notify.min.js') }}"></script>
<script src="{{asset('/theme_assets/js/bootstrap.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-en.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-en.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>


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


</script>
@yield('custom_script')
@yield('include_js')
</body>
</html>
