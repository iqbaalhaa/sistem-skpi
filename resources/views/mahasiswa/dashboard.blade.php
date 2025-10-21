@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-2">Selamat Datang, {{ Auth::user()->biodataMahasiswa?->nama ?? 'Mahasiswa' }}!</h4>
                            <p class="text-muted mb-0">
                                NIM: {{ Auth::user()->biodataMahasiswa?->nim ?? '-' }}
                                <span class="mx-2">•</span>
                                Status SKPI: 
                                @php
                                    $latestPengajuan = \App\Models\PengajuanSkpi::where('user_id', Auth::id())->latest('created_at')->first();
                                    if (!$latestPengajuan) {
                                        // Belum pernah mengajukan
                                        $label = '-';
                                        $badge = 'bg-secondary';
                                    } else {
                                        $status = $latestPengajuan->status ?? null;
                                        // treat 'menunggu' (or similar waiting states) as 'Dalam Proses'
                                        if ($status === 'menunggu') {
                                            $label = 'Dalam Proses';
                                            $badge = 'bg-warning';
                                        } elseif ($status === 'diterima_prodi' || $status === 'diterima_fakultas') {
                                            $label = 'Diterima';
                                            $badge = 'bg-success';
                                        } elseif ($status === 'ditolak_prodi' || $status === 'ditolak_fakultas') {
                                            $label = 'Ditolak';
                                            $badge = 'bg-danger';
                                        } else {
                                            $label = ucfirst($status ?? '-');
                                            $badge = 'bg-secondary';
                                        }
                                    }
                                @endphp
                                <span class="badge {{ $badge }}">{{ $label }}</span>
                            </p>
                        </div>
                        <div>
                            @php
                                $latestPengajuan = \App\Models\PengajuanSkpi::where('user_id', Auth::id())
                                    ->latest('created_at')
                                    ->first();
                                $status = $latestPengajuan->status ?? null;
                                $isSiapCetak = $status === 'diterima_fakultas';
                            @endphp

                            <a href="#"
                            class="btn btn-warning {{ $isSiapCetak ? '' : 'disabled' }}"
                            title="{{ $isSiapCetak ? 'Cetak SKPI Anda' : 'Belum dapat dicetak (menunggu tanda tangan fakultas)' }}">
                                <i class="bi bi-printer-fill"> Print SKPI Saya </i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height: 140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="font-semibold text-white-50 mb-3">Total Prestasi</h6>
                            <h3 class="mb-0 fw-bold">{{ Auth::user()->biodataMahasiswa?->prestasi()->count() ?? 0 }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="bi bi-trophy fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height: 140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="font-semibold text-white-50 mb-3">Pengalaman Organisasi</h6>
                            <h3 class="mb-0 fw-bold">{{ Auth::user()->biodataMahasiswa?->organisasi()->count() ?? 0 }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height: 140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="font-semibold text-white-50 mb-3">Sertifikat Bahasa</h6>
                            <h3 class="mb-0 fw-bold">{{ Auth::user()->biodataMahasiswa?->kompetensiBahasa()->count() ?? 0 }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="bi bi-translate fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height: 140px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="font-semibold text-white-50 mb-3">Pengalaman Magang</h6>
                            <h3 class="mb-0 fw-bold">{{ Auth::user()->biodataMahasiswa?->magang()->count() ?? 0 }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="bi bi-briefcase fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <br>
    <!-- Recent Activities and Progress -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @php
                            $activities = collect();
                            
                            // Gather activities from different tables
                            $prestasi = Auth::user()->prestasi()->latest('created_at')->take(5)->get()
                                ->map(function($item) {
                                    return [
                                        'type' => 'prestasi',
                                        'title' => $item->keterangan_indonesia,
                                        'icon' => 'bi-trophy',
                                        'bg' => 'bg-primary',
                                        'date' => $item->created_at
                                    ];
                                });

                            $organisasi = Auth::user()->organisasi()->latest('created_at')->take(5)->get()
                                ->map(function($item) {
                                    return [
                                        'type' => 'organisasi',
                                        'title' => $item->organisasi,
                                        'icon' => 'bi-people',
                                        'bg' => 'bg-success',
                                        'date' => $item->created_at
                                    ];
                                });

                            $bahasa = Auth::user()->kompetensiBahasa()->latest('created_at')->take(5)->get()
                                ->map(function($item) {
                                    return [
                                        'type' => 'bahasa',
                                        'title' => $item->nama_kompetensi . ' (' . $item->skor_kompetensi . ')',
                                        'icon' => 'bi-translate',
                                        'bg' => 'bg-info',
                                        'date' => $item->created_at
                                    ];
                                });

                            $magang = Auth::user()->magang()->latest('created_at')->take(5)->get()
                                ->map(function($item) {
                                    return [
                                        'type' => 'magang',
                                        'title' => $item->lembaga,
                                        'icon' => 'bi-briefcase',
                                        'bg' => 'bg-warning',
                                        'date' => $item->created_at
                                    ];
                                });

                            // Merge and sort all activities
                            $activities = $prestasi->concat($organisasi)
                                ->concat($bahasa)
                                ->concat($magang)
                                ->sortByDesc('date')
                                ->take(5);
                        @endphp

                        @forelse($activities as $activity)
                            <div class="feed-item {{ !$loop->last ? 'pb-3 mb-3 border-bottom' : '' }}">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="{{ $activity['bg'] }} rounded-circle p-2 me-3">
                                        <i class="bi {{ $activity['icon'] }} text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                        <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">
                                <p>Belum ada aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Kelengkapan SKPI</h5>
                </div>
                <div class="card-body">
                    @php
                        $biodata = Auth::user()->biodataMahasiswa;
                        // Simple biodata completeness: count required fields filled
                        $requiredBiodataFields = ['nama','nim','tempat_lahir','tanggal_lahir','tahun_masuk','tanggal_lulus','ipk','nama_prodi'];
                        $filled = 0;
                        if ($biodata) {
                            foreach ($requiredBiodataFields as $f) {
                                if (!empty($biodata->$f)) $filled++;
                            }
                        }
                        $biodataPercent = round(($filled / count($requiredBiodataFields)) * 100);

                        // Counts for other sections
                        $countPrestasi = Auth::user()->prestasi()->count();
                        $countOrganisasi = Auth::user()->organisasi()->count();
                        $countBahasa = Auth::user()->kompetensiBahasa()->count();
                        $countMagang = Auth::user()->magang()->count();

                        // Map counts to a 0-100 scale with reasonable caps
                        $prestasiPercent = min(100, $countPrestasi * 25); // each prestasi = 25% up to 100%
                        $organisasiPercent = min(100, $countOrganisasi * 20); // each org = 20%
                        $bahasaPercent = min(100, $countBahasa * 33); // each bahasa = 33%
                        $magangPercent = min(100, $countMagang * 50); // each magang = 50%
                    @endphp

                    <div class="progress-list">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Biodata</span>
                                <span>{{ $biodataPercent ?? 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $biodataPercent >= 80 ? 'bg-success' : ($biodataPercent >= 50 ? 'bg-warning' : 'bg-secondary') }}" role="progressbar" style="width: {{ $biodataPercent ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Prestasi</span>
                                <span>{{ $prestasiPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $prestasiPercent }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Pengalaman Organisasi</span>
                                <span>{{ $organisasiPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $organisasiPercent }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Sertifikasi</span>
                                <span>{{ $bahasaPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $bahasaPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="btn btn-outline-primary btn-block w-100">
                            <i class="bi bi-arrow-up-circle"></i> Tingkatkan Kelengkapan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
