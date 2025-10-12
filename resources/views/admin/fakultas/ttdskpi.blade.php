@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">
        Pengajuan SKPI - {{ $prodi->nama_prodi }}
    </h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Tanggal Diterima Prodi</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($item->user->biodataMahasiswa)->nama ?? '-' }}</td>
                            <td>{{ optional($item->user->biodataMahasiswa)->nim ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td>{{ $item->tanggal_verifikasi_prodi ? \Carbon\Carbon::parse($item->tanggal_verifikasi_prodi)->translatedFormat('d F Y') : '-' }}</td>
                            <td>
                                @if($item->status == 'diterima_prodi')
                                <form action="{{ route('fakultas.tandatangan', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> Tanda Tangan
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">Sudah ditandatangani</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada SKPI diterima oleh prodi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
