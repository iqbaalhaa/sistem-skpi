@extends('layouts.app')

@section('title', 'Dashboard Admin Prodi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Admin Prodi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Kelola Mahasiswa</h5>
                                    <p class="card-text">Manajemen data mahasiswa di prodi</p>
                                    <a href="#" class="btn btn-light">Kelola Mahasiswa</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Verifikasi SKPI</h5>
                                    <p class="card-text">Verifikasi dan validasi SKPI mahasiswa</p>
                                    <a href="#" class="btn btn-light">Verifikasi SKPI</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Laporan</h5>
                                    <p class="card-text">Generate laporan prodi</p>
                                    <a href="#" class="btn btn-light">Lihat Laporan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Selamat datang, {{ Auth::user()->username }}!</h5>
                                </div>
                                <div class="card-body">
                                    <p>Anda berhasil login sebagai Admin Prodi. Silakan gunakan menu di atas untuk mengakses fitur-fitur administrasi prodi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection