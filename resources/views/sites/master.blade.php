<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="/">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('client/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/elegant-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/magnific-popup.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/nice-select.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('client/css/style.css')}}" type="text/css">
</head>

<body>
    @include('sites.components.header')

    @yield('content')

    @include('sites.components.footer')
    @include('sites.components.search')

    <!-- Js Plugins -->
    <script src="{{asset('client/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('client/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('client/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('client/js/jquery.nicescroll.min.js')}}"></script>
    <script src="{{asset('client/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('client/js/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('client/js/jquery.slicknav.js')}}"></script>
    <script src="{{asset('client/js/mixitup.min.js')}}"></script>
    <script src="{{asset('client/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('client/js/main.js')}}"></script>
    <script>
        $(document).ready(function() {
            $(".search-btn").click(function() {
                $("#templatemo_search").modal("show");
            });
        })
    </script>
</body>

</html>
