@extends('layouts.app')

@section('title', 'Dashboard Admin Prodi')

@section('content')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .stat-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .stat-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card.warning {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .stat-card.info {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Dashboard height control */
    .dashboard-container {
        min-height: 100vh;
        max-height: 100vh;
        overflow-y: auto;
    }
    
    /* Chart container */
    .chart-container {
        height: 350px !important;
        position: relative;
    }
    
    /* Table scroll styling */
    .table-scroll {
        max-height: 400px;
        overflow-y: auto;
        border-radius: 0.375rem;
    }
    
    .table-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .table-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .table-scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .table-scroll::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Sticky header for table */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa;
    }
</style>

<div class="container-fluid dashboard-container">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm card-hover">
                <div class="card-body gradient-bg text-white rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 fw-bold">Selamat datang, {{ Auth::user()->username }}!</h2>
                            <p class="mb-0 opacity-75 fs-5">Dashboard Admin Prodi - Kelola dan pantau aktivitas mahasiswa dengan mudah</p>
                            <small class="opacity-50">{{ date('d F Y, H:i') }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-graph-up-arrow display-4 opacity-50 pulse"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body stat-card text-white rounded-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-uppercase mb-1 opacity-75">
                                Total Mahasiswa
                                </div>
                            <div class="h3 mb-0 fw-bold">{{ \App\Models\BiodataMahasiswa::count() }}</div>
                            <div class="text-xs opacity-75">
                                <i class="bi bi-arrow-up"></i> Aktif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body stat-card success text-white rounded-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-uppercase mb-1 opacity-75">
                                SKPI Tervalidasi
                                </div>
                            <div class="h3 mb-0 fw-bold">{{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->count() }}</div>
                            <div class="text-xs opacity-75">
                                <i class="bi bi-check-circle"></i> Selesai
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body stat-card warning text-white rounded-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-uppercase mb-1 opacity-75">
                                Menunggu Verifikasi
                                </div>
                            <div class="h3 mb-0 fw-bold">{{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->count() }}</div>
                            <div class="text-xs opacity-75">
                                <i class="bi bi-clock"></i> Perlu perhatian
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body stat-card info text-white rounded-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-uppercase mb-1 opacity-75">
                                Total Pengajuan
                                </div>
                            <div class="h3 mb-0 fw-bold">{{ \App\Models\PengajuanSkpi::count() }}</div>
                            <div class="text-xs opacity-75">
                                <i class="bi bi-file-earmark-text"></i> Semua
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Quick Actions -->
    <div class="row mb-4">
        <!-- Chart Section -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0">
                    <h6 class="m-0 fw-bold text-primary">Statistik SKPI per Bulan</h6>
                </div>
                <div class="card-body chart-container">
                    <canvas id="skpiChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-header border-0">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-lightning-charge me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('prodi.mahasiswa.index') }}" class="btn btn-primary btn-lg mb-2 shadow-sm">
                            <i class="bi bi-people me-2"></i>Kelola Mahasiswa
                            <small class="d-block opacity-75">Manajemen data mahasiswa</small>
                        </a>
                        <a href="{{ route('prodi.verifikasi') }}" class="btn btn-success btn-lg mb-2 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Verifikasi SKPI
                            <small class="d-block opacity-75">Validasi pengajuan SKPI</small>
                        </a>
                        <a href="#" class="btn btn-warning btn-lg mb-2 shadow-sm">
                            <i class="bi bi-file-earmark-text me-2"></i>Generate Laporan
                            <small class="d-block opacity-75">Export data prodi</small>
                        </a>
                        <a href="#" class="btn btn-info btn-lg shadow-sm">
                            <i class="bi bi-gear me-2"></i>Pengaturan Prodi
                            <small class="d-block opacity-75">Konfigurasi sistem</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm card-hover">
                <div class="card-header border-0">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-scroll">
                        <table class="table table-hover">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th><i class="bi bi-calendar-event me-1"></i>Waktu</th>
                                    <th><i class="bi bi-person me-1"></i>Mahasiswa</th>
                                    <th><i class="bi bi-activity me-1"></i>Aktivitas</th>
                                    <th><i class="bi bi-flag me-1"></i>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\PengajuanSkpi::with('biodataMahasiswa')->latest()->take(10)->get() as $pengajuan)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $pengajuan->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                {{ substr($pengajuan->biodataMahasiswa->nama ?? 'N/A', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $pengajuan->biodataMahasiswa->nama ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $pengajuan->biodataMahasiswa->nim ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">Pengajuan SKPI</span>
                                        <br>
                                        <small class="text-muted">ID: #{{ $pengajuan->id }}</small>
                                    </td>
                                    <td>
                                        @if($pengajuan->status == 'menunggu')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Menunggu
                                            </span>
                                        @elseif($pengajuan->status == 'diverifikasi')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada aktivitas terbaru</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(\App\Models\PengajuanSkpi::count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('prodi.verifikasi') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // SKPI Chart with real data
    const ctx = document.getElementById('skpiChart').getContext('2d');
    
    // Get real data from Laravel
    const chartData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        tervalidasi: [
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 1)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 2)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 3)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 4)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 5)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 6)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 7)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 8)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 9)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 10)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 11)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'diverifikasi')->whereMonth('created_at', 12)->count() }}
        ],
        menunggu: [
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 1)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 2)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 3)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 4)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 5)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 6)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 7)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 8)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 9)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 10)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 11)->count() }},
            {{ \App\Models\PengajuanSkpi::where('status', 'menunggu')->whereMonth('created_at', 12)->count() }}
        ]
    };
    
    const skpiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'SKPI Tervalidasi',
                data: chartData.tervalidasi,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(75, 192, 192)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'SKPI Menunggu',
                data: chartData.menunggu,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    // Add loading animation
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush
@endsection