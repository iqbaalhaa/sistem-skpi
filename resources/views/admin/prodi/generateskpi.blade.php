@extends('layouts.app')

@section('title', 'Generate SKPI')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Generate SKPI</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Generate SKPI</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswa as $index => $mhs)
                            @php
                                // Cek status pengajuan SKPI terbaru
                                $latestPengajuan = $mhs->user->pengajuanSkpi->first();
                                $canGenerate = $latestPengajuan && $latestPengajuan->status === 'diterima_fakultas';
                            @endphp
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($canGenerate)
                                        <form method="POST" action="{{ route('prodi.generateskpi.generate', $mhs->user->id) }}" target="_blank">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" title="Generate SKPI">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm" disabled title="Belum ditandatangani fakultas">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>{{ $mhs->user->email }}</td>
                                <td>
                                    @if($latestPengajuan)
                                        @switch($latestPengajuan->status)
                                            @case('menunggu')
                                                <span class="badge bg-warning">Menunggu</span>
                                                @break
                                            @case('diterima_prodi')
                                                <span class="badge bg-info">Diterima Prodi</span>
                                                @break
                                            @case('diterima_fakultas')
                                                <span class="badge bg-success">Ditandatangani</span>
                                                @break
                                            @case('ditolak_prodi')
                                                <span class="badge bg-danger">Ditolak Prodi</span>
                                                @break
                                            @case('ditolak_fakultas')
                                                <span class="badge bg-danger">Ditolak Fakultas</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($latestPengajuan->status) }}</span>
                                        @endswitch
                                    @else
                                        <span class="badge bg-secondary">Belum Mengajukan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

