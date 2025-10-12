@extends('layouts.app')

@section('title', 'Biodata Admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body text-center">

                    {{-- Foto Admin --}}
                    <div class="mb-3 text-center">
                        @if($admin->biodataAdmin && $admin->biodataAdmin->foto)
                            <img src="{{ asset('storage/' . $admin->biodataAdmin->foto) }}"
                                alt="Foto Admin"
                                class="img-fluid rounded"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}"
                                alt="Default Foto"
                                class="img-fluid rounded"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    
                    {{-- Nama & Jabatan --}}
                    <p>Nama: {{ Auth::user()->biodataAdmin->nama ?? '-' }}</p>
                    <hr>

                    {{-- Detail Info --}}
                    <div class="text-start px-4">
                        <p><strong>Email:</strong> {{ $admin->email }}</p>
                        <p><strong>Role:</strong> {{ ucfirst($admin->role) }}</p>
                    </div>

                    {{-- Tombol Edit --}}
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editBiodataModal">
                            <i class="bi bi-pencil-square"></i> Edit Biodata
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Biodata -->
<div class="modal fade" id="editBiodataModal" tabindex="-1" aria-labelledby="editBiodataModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.biodata.update', $admin->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="editBiodataModalLabel">Edit Biodata Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $admin->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" class="form-control">
                @if($admin->foto)
                    <small class="text-muted">Foto saat ini: {{ $admin->foto }}</small>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
    </form>
  </div>
</div>

@endsection
