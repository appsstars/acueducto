<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Acuedcuto')</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap-material-design.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/popper.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script src="{{asset('js/chart.min.js')}}"></script>


</head>
<body>
    <div id="app">
      <div class="container" style="padding: 30px 30px 0 30px">
        <div class="row super-cabecera"  id="cabecera">
          <div class="col-md-2">
              <div class="top-menu">
                <img src="{{url('img/logo.png')}}">
              </div>
            </div>
            <div class="col-md-4">
              <span class="title">ACUEDUCTO VEREDA</span>  </br>
              <span class="title">PIJAOS </span> </br>
              <span class="title">MUNICIPIO DE </span> </br>
              <span class="title">CUCAITA NIT </span> </br>
              <span class="title">900087531-8 </span>  </br>
          </div>
        </div>
      </div>
        <header>
            <div class="container">
                <nav class="navbar " style="margin-top: 10px">
                  <a class="navbar-brand" href="/produccion/acueducto/public/app" style="margin:auto"><i class="fa fa-home" style="font-size: 40px !important;"></i></a>
                  <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="fa fa-sign-out" style="font-size: 35px;"></i>
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                  </form>                
                </nav>
            </div>

        </header>
        <main class="py-4">
            @yield('content')
        </main>
    </div>


</body>
</html>
