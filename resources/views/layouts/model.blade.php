

    {{--CSS--}}


    @yield('include_css')
</head>

<body>

<div class="container-fluid">

@yield('content')

</div>


<script type="text/javascript">
    $('.select2').select2();

</script>

@yield('custom_script')
@yield('include_js')


