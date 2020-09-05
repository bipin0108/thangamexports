<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>RockOutLoud</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('public/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/jquery.magnify.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/zoom.css') }}"> 
  <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
  <link rel="icon" href="{{ asset('public/image/logo.png') }}" type="image/gif" sizes="16x16">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="{{ asset('public/plugins/jquery/jquery.min.js') }}"></script>
</head>
<body class="sidebar-mini control-sidebar-slide-open text-sm">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand border-bottom-0 text-sm navbar-dark navbar-gray-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ url('/') }}" class="brand-link">
          <img src="{{ asset('public/image/logo.png') }}" alt="{{ env('APP_NAME', '') }} Logo" class="brand-image elevation-3">
          <span class="brand-text font-weight-light">{{ env('APP_NAME', '') }}</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('public/image/profile.png') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="{{ url('/profile') }}" class="d-block">Hello, Admin</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-flat nav-legacy nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ (request()->is('dashboard') || request()->is('/')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('category.index') }}" class="nav-link {{ (request()->is('category*')) || (request()->is('sub-category*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-paper-plane"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('product.index') }}" class="nav-link {{ (request()->is('product*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-shopping-cart"></i>
                  <p>Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ (request()->is('user*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-alt"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/slider') }}" class="nav-link {{ (request()->is('slider*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-image"></i>
                  <p>Slider</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/logout') }}" class="nav-link">
                  <i class="nav-icon fas fa-sign-out-alt"></i>
                  <p>Logout</p>
                </a>
              </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    @yield('content')

    <footer class="main-footer text-sm">
      <div class="float-right d-none d-sm-block">
        
      </div>
      <strong>Copyright &copy; 2020 <a href="{{ url('/') }}">{{ env('APP_NAME', '') }}</a>.</strong> All rights
      reserved.
    </footer>
  </div>
  <!-- ./wrapper -->
  
  <!-- jQuery -->
  <script src="{{ asset('public/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/demo.js') }}"></script>
  <script src="{{ asset('public/dist/js/jquery.magnify.js') }}"></script>
  
  <script>
    $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
      localStorage.setItem('activeTab', $(e.target).attr('id'));
    });
 
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#custom-tabs-four-tab #' + activeTab).tab('show').trigger('click');
    }

    $('[data-magnify]').magnify({
      resizable: false,
      headToolbar: [
        'close'
      ],
      initMaximized: true
    });
  </script>

</body>
</html>