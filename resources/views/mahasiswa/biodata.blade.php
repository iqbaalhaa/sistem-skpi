@extends('layouts.app')

@section('title', 'Biodata Mahasiswa')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Biodata Mahasiswa</h1>
    <div class="card shadow">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="{{ isset($biodata) && $biodata->foto ? asset('storage/foto_mahasiswa/' . $biodata->foto) : asset('images/default-user.png') }}" alt="Foto Mahasiswa" class="img-thumbnail rounded-circle" style="width: 160px; height: 160px; object-fit: cover;">
                    <br>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalEditBiodata">Edit</button>
                </div>
                <div class="col-md-9">
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">NIM</div>
                        <div class="col-sm-8">{{ $biodata->nim ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Nama</div>
                        <div class="col-sm-8">{{ $biodata->nama ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Program Studi</div>
                        <div class="col-sm-8">{{ $biodata->prodi->nama_prodi ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Tempat, Tanggal Lahir</div>
                        <div class="col-sm-8">{{ $biodata->tempat_lahir ?? '-' }}, {{ $biodata->tanggal_lahir ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Alamat</div>
                        <div class="col-sm-8">{{ $biodata->alamat ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">No. HP</div>
                        <div class="col-sm-8">{{ $biodata->no_hp ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Tahun Masuk</div>
                        <div class="col-sm-8">{{ $biodata->tahun_masuk ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Biodata -->
    <div class="modal fade" id="modalEditBiodata" tabindex="-1" aria-labelledby="modalEditBiodataLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditBiodataLabel">Edit Biodata Mahasiswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('mahasiswa.biodata.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img src="{{ isset($biodata) && $biodata->foto ? asset('storage/foto_mahasiswa/' . $biodata->foto) : asset('images/default-user.png') }}" alt="Foto Mahasiswa" class="img-thumbnail rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                    <input type="file" name="foto" class="form-control form-control-sm mt-2">
                </div>
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="number" name="nim" id="nim" class="form-control" value="{{ $biodata->nim ?? '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ $biodata->nama ?? '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="prodi_id" class="form-label">Program Studi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ (isset($biodata) && $biodata->prodi_id == $prodi->id) ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ $biodata->tempat_lahir ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ $biodata->tanggal_lahir ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control">{{ $biodata->alamat ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ $biodata->no_hp ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" min="2000" max="2099" value="{{ $biodata->tahun_masuk ?? '' }}">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection
