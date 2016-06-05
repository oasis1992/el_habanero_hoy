<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title','Campus M&eacute;rida')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" charset="utf-8">
    <link rel="stylesheet" href="{{  asset('css/web/main.css') }}">
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.19.1/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{asset('plugins/chosen/css/chosen.css')}}"  media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">


</head>
<body>
<section>
    <div class="first_container">
        @yield('content')
    </div>
</section>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}" ></script>
<script src="{{asset('plugins/chosen/js/jquery-chosen.js')}}"></script>

@yield('js')
</body>
</html>
