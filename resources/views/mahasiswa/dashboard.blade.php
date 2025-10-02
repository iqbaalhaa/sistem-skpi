@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Mahasiswa</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">SKPI Saya</h5>
                                    <p class="card-text">Lihat dan kelola Surat Keterangan Pendamping Ijazah Anda</p>
                                    <a href="#" class="btn btn-light">Lihat SKPI</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Aktivitas</h5>
                                    <p class="card-text">Riwayat aktivitas dan pencapaian Anda</p>
                                    <a href="#" class="btn btn-light">Lihat Aktivitas</a>
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
                                    <p>Anda berhasil login sebagai Mahasiswa. Silakan gunakan menu di atas untuk mengakses fitur-fitur yang tersedia.</p>
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
