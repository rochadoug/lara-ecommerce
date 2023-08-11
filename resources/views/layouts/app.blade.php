<!DOCTYPE html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="host" content="{{ url('/') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teste Desenvolvedor Web - UNIASSELVI') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito|Lobster|Carter+One" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Components -->
    <link rel="stylesheet" href="{{ asset('components/bootstrap-4.2.1/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('components/fontawesome/css/all.css') }}">

    <link rel="stylesheet" href="{{ asset('components/jquery-ui-1.12.1.datepicker_redmond/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('components/jquery-ui-1.12.1.datepicker_redmond/jquery-ui.theme.min.css') }}">

    <link href="{{ asset('css/card.less.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layouts.css') }}" rel="stylesheet">

    @yield('custom_css')
</head>
<body class="d-flex flex-column h-100">
    <!-- <div id="app"> -->
        <nav class="navbar navbar-expand-md mb-3 pb-3 navbar-dark navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Teste Desenvolvedor Web - UNIASSELVI') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">

                                <!-- <div class="dropdown">
                                  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Dropdown trigger
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dLabel">
                                    ...
                                  </div>
                                </div> -->

                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->Cliente->nome }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('/') }}/historico">{{ __('Pedidos') }}</a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Sair') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                            <li class="nav-item">
                                <a href="{{ url('/') }}/carrinho" class="nav-link px-3 bg-light rounded text-primary"><i class="fas fa-shopping-cart"></i>
                                @if(session('cart')!=null and count(session('cart')) > 0)
                                <span class="badge badge-dark">{{ count(session('cart')) }}</span>
                                @endif
                                </a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-shrink-0 py-4" role="main">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- <footer class="footer mt-auto py-3"> -->
        <footer class="footer mt-auto pt-4 text-success">
            <div class="container">
                <div class="row d-flex justify-content-between  ">

                    <div class="col-auto">
                        <h5>Desenvolvido por</h5>
                        <ul class="list-unstyled text-small">
                            <li><a class="text-light" href="#">Pyetro Costa</a></li>
                            <!-- <li><img class="mb-2" src="{{ asset('') }}" alt="" width="24" height="24"></li> -->
                            <li><small class="d-block mb-3 text-light">&copy; 2018-2019</small></li>
                        </ul>
                    </div>

                    <div class="col-auto">
                        <h5>Contato</h5>
                        <ul class="list-unstyled text-small">
                            <li><a class="text-light" href="#">(14) 3451-7070</a></li>
                            <li><a class="text-light" href="#">contato@uniasselvi.com.br</a></li>
                            <li><a class="text-light" href="#">Rua Eugênio Pessini, 63</a></li>
                            <li><a class="text-light" href="#">Marília-SP</a></li>
                        </ul>
                    </div>

                    <div class="col-auto">
                        <h5>Horário de Atendimento</h5>
                        <ul class="list-unstyled text-small">
                            <li><a class="text-light" href="#">Aberto das 18h00 às 00h00</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" --defer></script>

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- <script src="https://code.jquery.com/jquery-migrate-3.0.1.min.js" integrity="sha256-F0O1TmEa4I8N24nY0bya59eP6svWcshqX1uzwaWC4F4=" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script> -->

    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

    <!-- <script src="{{ asset('components/cloudflare/ajax/libs/popper.js/umd/popper.min.js') }}"></script>
    <script src="{{ asset('components/bootstrap-4.2.1/dist/js/bootstrap.min.js') }}"></script> -->

    <script src="{{ asset('components/moment/moment.js') }}"></script>

    <script src="{{ asset('components/jquery-ui-1.12.1.datepicker_redmond/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('components/jquery-ui/ui/i18n/datepicker-pt-BR.js') }}"></script>

    <script>
        $(function() {
            console.log( "ready!" );
            console.log( jQuery.fn.jquery );
        });
    </script>

    @yield('custom_js')
</body>
</html>
