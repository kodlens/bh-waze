<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('extracss')
    @yield('extrajs')

</head>
<body>
    <div id="app">
        @if(Auth::check())
            <navbar-land-owner prop-user='{{ Auth::user() }}'></navbar-land-owner>
        @else
            <navbar-land-owner prop-user=''></navbar-land-owner>
        @endif


        <div>
            @yield('content')
        </div>


    </div>

    @yield('extrabottomjs')

</body>
</html>
