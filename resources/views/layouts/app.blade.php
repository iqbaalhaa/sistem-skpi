<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard -Admin Dashboard')</title>

  <link rel="shortcut icon" href="{{ asset('backend/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/app-dark.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/iconly.css') }}">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/extensions/sweetalert2/sweetalert2.min.css') }}">
  <!-- Theme Override - Must be last -->
  <link rel="stylesheet" href="{{ asset('backend/assets/compiled/css/theme-override.css') }}">


  <style>

  /* hover state: langsung berubah jadi biru muda */
  .card-hover:hover {
      background:radial-gradient(circle,rgba(255, 255, 255, 1) 0%, rgba(17, 114, 188, 1) 70%); !important;            /* solid light-blue on hover */
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 12px 30px rgba(13, 110, 253, 0.12);
  }

  /* ubah warna teks/ikon saat hover supaya kontras */
  .card-hover:hover .card-title,
  .card-hover:hover .text-muted,
  .card-hover:hover .icon-wrap i {
      color:rgb(0, 57, 143) !important; /* bootstrap primary blue */
  }

  .card-hover:hover .badge {
    transform: scale(1.1);
    transition: 0.2s ease;
}

  /* ikon lebih visible */
  .icon-wrap i {
      color: #0dcaf0; /* default indigo-ish */
      transition: color 0.25s ease !important;
  }

  /* transisi halus untuk teks */
  .card-title {
      transition: color 0.25s ease !important;
  }

  /* mobile: jangan gunakan transform scale berlebihan */
  @media (max-width: 576px) {
      .card-hover:hover {
          transform: translateY(-4px) !important;
      }
  }
  </style>

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
            @if(Auth::user()->biodataMahasiswa)
              <img src="{{ asset('storage/foto_mahasiswa/' . Auth::user()->biodataMahasiswa->foto) }}" alt="Foto Profil" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
            @endif
            <div class="ms-3">
              @if(Auth::user()->role == 'mahasiswa')
                <h6 class="mb-0">{{ Auth::user()->biodataMahasiswa?->nama ?? Auth::user()->username }}</h6>
                <p class="mb-0 text-muted small">{{ Auth::user()->biodataMahasiswa?->nim ?? '-' }}</p>
              @else
                <h6 class="mb-0">{{ Auth::user()->username }}</h6>
                <p class="mb-0 text-muted small">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
              @endif
            </div>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm ms-3">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </div>
        </div>
        @endif
      </header>

      {{-- Page Content --}}
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
  <!-- SweetAlert2 JS -->
  <script src="{{ asset('backend/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
  @stack('scripts')
</body>
</html>
