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
        <div class="row" style="margin-top: 50px;border: 1px #000 dotted" id="cabecera">
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
                <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-top: 10px">
                  <a class="navbar-brand" href="/app"><i class="fa fa-home"></i> PIJAOS</a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">

                      <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                SALIR
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                      </li>

                    </ul>
                  </div>
                </nav>
            </div>

        </header>
        <main class="py-4">
            @yield('content')
        </main>
    </div>


</body>
</html>
