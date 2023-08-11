<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="host" content="{{ url('/') }}/admin">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teste Desenvolvedor Web - UNIASSELVI') }} - Admin</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('components/bootstrap-4.2.1/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('components/fontawesome/css/all.css') }}">

    <!-- App Custom Css -->
    <link href="{{ asset('css/table.less.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form.less.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('components/jquery-ui-1.12.1.datepicker_redmond/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('components/jquery-ui-1.12.1.datepicker_redmond/jquery-ui.theme.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('components/daterangepicker/daterangepicker.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('components/jQuery-autoComplete-master/jQuery.auto-complete.css') }}" />

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">

    @yield('custom_css')

  </head>
  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-3 col-lg-2 mr-0 d-block" href="{{ url('/') }}/admin">{{ config('app.name', 'Teste Desenvolvedor Web - UNIASSELVI') }}</a>
        <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
        <ul class="navbar-nav px-3 mx-1 bg-secondary">
            <li class="nav-item text-nowrap">
                <a class="nav-link text-white px-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              {{-- <li class="nav-item">
                <a class="nav-link {{ (join('/', Request::segments()) == 'admin/grafico' ? 'active':'') }}" href="{{ url('/') }}/admin/grafico">
                  <span data-feather="home"></span> Gráficos <span class="sr-only">(current)</span>
                </a>
              </li> --}}
              <li class="nav-item">
                <a class="nav-link {{ (join('/', Request::segments()) == 'admin/pedidos' ? 'active':'') }}" href="{{ url('/') }}/admin/pedidos">
                  <span data-feather="file"></span> Pedidos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ (join('/', Request::segments()) == 'admin/produtos' ? 'active':'') }}" href="{{ url('/') }}/admin/produtos">
                  <span data-feather="shopping-cart"></span> Produtos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ (join('/', Request::segments()) == 'admin/clientes' ? 'active':'') }}" href="{{ url('/') }}/admin/clientes">
                  <span data-feather="users"></span> Clientes
                </a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="bar-chart-2"></span>
                  Reports
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="layers"></span>
                  Integrations
                </a>
              </li> -->
            </ul>

            <!-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Saved reports</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Current month
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Last quarter
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Social engagement
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Year-end sale
                </a>
              </li>
            </ul> -->
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

            @yield('content')

        </main>
      </div>
    </div>

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('components/bootstrap-4.2.1/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="{{ asset('components/cloudflare/ajax/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('components/cloudflare/ajax/libs/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('components/daterangepicker/daterangepicker.min.js') }}"></script>

    <script src="{{ asset('components/jQuery-autoComplete-master/jQuery.auto-complete.min.js') }}"></script>

    <script>
        $(function() {
            feather.replace();

          // Calendar
            $(':input.dtrangepicker').mask('00/00/0000 - 00/00/0000');
            $(':input.dtrangepicker').daterangepicker({
              autoUpdateInput: false,
              'locale': {
                  'format': 'DD/MM/YYYY',
                  'separator': ' - ',
                  'applyLabel': 'Aplicar',
                  'cancelLabel': 'Cancelar',
                  'fromLabel': 'De',
                  'toLabel': 'Para',
                  'customRangeLabel': 'Custom',
                  'weekLabel': 'W',
                  'daysOfWeek': [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab' ],
                  'monthNames': [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
                  'firstDay': 1
              },
              'showDropdowns': true,
              opens: 'left',
              autoUpdateInput: true,
              startDate: moment().subtract(7, 'days'),
              endDate: new Date()
            }, function(start, end, label) {
              var $inp = $(this.element),
                  $form = $('#filterDate');

              //$inp.val(start+' - '+end);
              //setInterval(function() { $form[0].submit(); }, 100);

              start = moment(start).format('YYYY-MM-DD');
              end = moment(end).format('YYYY-MM-DD');
              console.log( start, end );
              $inp.val(start+' - '+end);
              $form[0].submit();
            });

            $(':input.page_selector').on('change', function() {
                $form = $('#filterDate');
                $form[0].submit();
            })
        });
    </script>
    @yield('custom_js')

  </body>
</html>
