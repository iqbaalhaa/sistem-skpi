<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard -Admin Dashboard')</title>

  <link rel="shortcut icon" href="{{ asset('backend/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/app-dark.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/iconly.css') }}">
</head>

<body>
  <script src="{{ asset('backend/assets/static/js/initTheme.js') }}"></script>
  <div id="app">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div id="main">
      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
        
        @if(Auth::check())
        <div class="d-flex justify-content-between align-items-center">
          <div></div>
          <div class="d-flex align-items-center">
            <span class="me-3">Selamat datang, {{ Auth::user()->username }} ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})</span>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </div>
        </div>
        @endif
      </header>

      {{-- Page Content --}}
      <div class="page-heading">
      </div>
      <div class="page-content">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer')
    </div>
  </div>

  <script src="{{ asset('backend/assets/static/js/components/dark.js') }}"></script>
  <script src="{{ asset('backend/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('backend/assets/compiled/js/app.js') }}"></script>
  <script src="{{ asset('backend/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('backend/assets/static/js/pages/dashboard.js') }}"></script>
  <script src="{{ asset('backend/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
  <script src="{{ asset('backend/assets/static/js/pages/simple-datatables.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
