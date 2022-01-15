<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <link rel="shortcut icon" href="{{asset('/theme_assets/img/favicon.ico')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title>{{getConfigArrayValueByKey('COMPANY_DETAILS','company_name')}}</title>


    <link href="{{asset('/theme_assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/theme_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/theme_assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />



</head>
<body class="account-body accountbg">

    @yield('content')


</body>


<script src="{{asset('/theme_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('/theme_assets/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('/theme_assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/theme_assets/js/metismenu.min.js')}}"></script>
<script src="{{asset('/theme_assets/js/waves.js')}}"></script>
<script src="{{asset('/theme_assets/js/feather.min.js')}}"></script>
<script src="{{asset('/theme_assets/js/jquery.slimscroll.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('/theme_assets/js/app.js')}}"></script>

</html>
