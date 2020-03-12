<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon and Touch Icons -->
        <link href="{{ asset('inc/images/favicon.ico') }}" rel="shortcut icon" type="image/png">
        <link href="{{ asset('inc/images/apple-touch-icon.png') }}') }}" rel="icon">
        <link href="{{ asset('inc/images/apple-touch-icon-72x72.png') }}') }}" rel="icon" sizes="72x72">
        <link href="{{ asset('inc/images/apple-touch-icon-114x114.png') }}') }}" rel="icon" sizes="114x114">
        <link href="{{ asset('inc/images/apple-touch-icon-144x144.png') }}') }}" rel="icon" sizes="144x144">

        <title>Welcome To HospitAll</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('backend/docs/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('backend/docs/js/jquery-3.3.1.min.js') }}" type="text/javascript" ></script>
        <script src="{{ asset('backend/docs/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @yield('content')
            
        </div>
    </body>
</html>
