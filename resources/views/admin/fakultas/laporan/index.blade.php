@extends('layouts.app')

@section('title', 'Laporan SKPI Fakultas')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Laporan SKPI Fakultas</h4>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.fakultas.laporan.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="prodi" class="form-label">Filter Prodi</label>
                    <select name="prodi" id="prodi" class="form-select">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodiList as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="{{ route('admin.fakultas.laporan.export', ['prodi' => request('prodi')]) }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Disetujui</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->user->biodataMahasiswa->nama ?? '-' }}</td>
                            <td>{{ $data->user->biodataMahasiswa->nim ?? '-' }}</td>
                            <td>{{ $data->user->biodataMahasiswa->prodi->nama_prodi ?? '-' }}</td>
                            <td>{{ $data->certificate->nomor_surat ?? 'Belum Digenerate' }}</td>
                            <td>{{ $data->updated_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data SKPI disetujui</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
