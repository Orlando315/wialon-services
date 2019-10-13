<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>@yield('title', config('app.name'))</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <!-- CSS Files -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/light-bootstrap-dashboard.css?v=2.0.0') }}" rel="stylesheet" />
    <!-- Datatabes -->
    <link href="{{ asset('js/plugins/datatables/datatables.min.css') }}" rel="stylesheet" />
    <!-- App -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    @yield('head', '')

  </head>
  <body>
    <div class="wrapper">
      <div class="sidebar" data-color="red">
        <div class="sidebar-wrapper">
          <div class="logo">
            <a href="{{ route('dashboard') }}" class="simple-text">
              {{ config('app.name') }}
            </a>
          </div>
          <ul class="nav">            
            @if(Auth::user()->isAdmin())
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fa fa-users"></i>
                <p>Usuarios</p>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.planes.index') }}">
                <i class="fa fa-list-ul"></i>
                <p>Planes</p>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.facturas.index') }}">
                <i class="fa fa-list-alt"></i>
                <p>Facturas</p>
              </a>
            </li>
            @endif

            <li class="nav-item">
              <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fa fa-podcast"></i>
                <p>Servicios</p>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('facturas.index') }}">
                <i class="fa fa-list-alt"></i>
                <p>Facturas</p>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('pagos.index') }}">
                <i class="fa fa-credit-card"></i>
                <p>Pagos</p>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg " color-on-scroll="500">
          <div class="container-fluid">

            @yield('brand', '')

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-bar burger-lines"></span>
              <span class="navbar-toggler-bar burger-lines"></span>
              <span class="navbar-toggler-bar burger-lines"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
              <ul class="nav navbar-nav mr-auto">
              </ul>
              <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                  <a href="{{ route('suscripciones.planes') }}" class="nav-link" rel="tooltip" title="Agregar servicio">
                    <i class="fa fa-plus" aria-hidden="true"></i> Agregar servicio
                  </a>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->email }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item" href="{{ route('perfil') }}">
                        <i class="fa fa-user"></i> Mi perfil
                      </a>
                      <form action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button class="dropdown-item text-danger" type="submit">
                          <i class="fa fa-sign-out" aria-hidden="true" style="line-height: 1"></i> Salir
                        </button>
                      </form>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        
        <!-- End Navbar -->
        <div class="content">
          <div class="container-fluid">

            @yield('content')

          </div>
        </div>
        <footer class="footer">
          <div class="container-fluid">
            <nav>
              <p class="copyright text-center">
              </p>
            </nav>
          </div>
        </footer>
      </div><!-- Main-panel -->
    </div>
    <!-- Core JS Files -->
    <script src="{{ asset('js/core/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
    <script src="{{ asset('js/light-bootstrap-dashboard.js?v=2.0.0') }}" type="text/javascript"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Data-table -->
    <script src="{{ asset('js/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
      $(document).ready(function(){
        $('div.alert').not('.alert-important').delay(7000).slideUp(300);

        $('.data-table').DataTable({
          responsive: true,
          language: {
            url:'{{ asset("js/plugins/datatables/spanish.json") }}'
          },
          pageLength: 25
        });

        $('.data-table-no-responsive').DataTable({
          language: {
            url:'{{ asset("js/plugins/datatables/spanish.json") }}'
          },
          pageLength: 25
        });

        jQuery.fn.hasHScrollBar = function(){
          return this.get(0).scrollWidth > this.innerWidth();
        }

        $('.table-responsive .dropdown').on('show.bs.dropdown', function (e) {
          var $table = $(this).closest('.table-responsive');
          if(!$table.hasHScrollBar()){
            $('.table-responsive').css('overflow', 'visible');
          }
        });

        $('.table-responsive .dropdown').on('shown.bs.dropdown', function (e) {
          var $table = $(this).closest('.table-responsive');

          if($table.hasHScrollBar()){
            var $menu = $(this).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);

            if (menuOffsetHeight > tableOffsetHeight)
              $table.css('padding-bottom', menuOffsetHeight - tableOffsetHeight + 15);
          }

        });

        $('.table-responsive .dropdown').on('hide.bs.dropdown', function () {
          $(this).closest('.table-responsive').css({'padding-bottom':'', 'overflow':''});
        })

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        });
      })
    </script>

    @yield('scripts')

  </body>
</html>
