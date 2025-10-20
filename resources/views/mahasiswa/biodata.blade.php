@extends('layouts.app')

@section('title', 'Biodata Mahasiswa')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Sidebar Profil -->
                <div class="col-md-4 border-end text-center">
                    <img src="{{ isset($biodata) && $biodata->foto ? asset('storage/foto_mahasiswa/' . $biodata->foto) : asset('images/default-user.png') }}"
                         alt="Foto Mahasiswa"
                         class="rounded-circle img-thumbnail mb-3"
                         style="width: 160px; height: 160px; object-fit: cover;">

                    <h5 class="fw-bold">{{ $biodata->nama ?? '-' }}</h5>
                    <p class="text-muted mb-1">{{ $biodata->prodi->nama_prodi ?? '-' }}</p>
                    <p class="small text-secondary">{{ $biodata->nim ?? '-' }}</p>

                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditBiodata">
                        <i class="bi bi-pencil"></i> Edit Profil
                    </button>

                    <hr class="my-4">

                    <ul class="list-unstyled text-start px-4">
                        <li class="mb-2"><i class="bi bi-geo-alt text-primary"></i> {{ $biodata->alamat ?? '-' }}</li>
                        <li class="mb-2"><i class="bi bi-telephone text-primary"></i> {{ $biodata->no_hp ?? '-' }}</li>
                        <li class="mb-2"><i class="bi bi-calendar3 text-primary"></i> Tahun Masuk: {{ $biodata->tahun_masuk ?? '-' }}</li>
                        <li class="mb-2"><i class="bi bi-cake text-primary"></i> {{ $biodata->tempat_lahir ?? '-' }}, {{ $biodata->tanggal_lahir ?? '-' }}</li>
                    </ul>
                </div>

                <!-- Detail Akademik -->
                <div class="col-md-8">
                    <h5 class="fw-bold text-primary mb-3">Informasi Akademik</h5>
                    <div class="row mb-2">
                        <div class="col-sm-5 fw-semibold">Judul Skripsi</div>
                        <div class="col-sm-7">{{ $biodata->judul_skripsi ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5 fw-semibold">Lama Studi</div>
                        <div class="col-sm-7">{{ $biodata->lama_studi ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5 fw-semibold">Tanggal Lulus</div>
                        <div class="col-sm-7">{{ $biodata->tanggal_lulus ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5 fw-semibold">Nomor Ijazah</div>
                        <div class="col-sm-7">{{ $biodata->nomor_ijazah ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5 fw-semibold">IPK</div>
                        <div class="col-sm-7">{{ $biodata->ipk ?? '-' }}</div>
                    </div>

                    <hr class="my-4">

                    <div class="text-muted small">
                        <p class="mb-1">Terakhir diperbarui: {{ isset($biodata) && $biodata && $biodata->updated_at ? $biodata->updated_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Biodata -->
    <div class="modal fade" id="modalEditBiodata" tabindex="-1" aria-labelledby="modalEditBiodataLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditBiodataLabel">Edit Biodata Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mahasiswa.biodata.update') }}" method="POST" enctype="multipart/form-data">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4 text-center">
                                <img src="{{ isset($biodata) && $biodata->foto ? asset('storage/foto_mahasiswa/' . $biodata->foto) : asset('images/default-user.png') }}"
                                     alt="Foto Mahasiswa"
                                     class="rounded-circle img-thumbnail mb-2"
                                     style="width: 140px; height: 140px; object-fit: cover;">
                                <input type="file" name="foto" class="form-control form-control-sm mt-2">
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" name="nim" id="nim" class="form-control" value="{{ $biodata->nim ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $biodata->nama ?? '' }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="prodi_id" class="form-label">Program Studi</label>
                                        <select name="prodi_id" id="prodi_id" class="form-select">
                                            <option value="">-- Pilih Prodi --</option>
                                            @foreach($prodis as $prodi)
                                                <option value="{{ $prodi->id }}" {{ (isset($biodata) && $biodata->prodi_id == $prodi->id) ? 'selected' : '' }}>
                                                    {{ $prodi->nama_prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ $biodata->tempat_lahir ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ $biodata->tanggal_lahir ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" class="form-control">{{ $biodata->alamat ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="no_hp" class="form-label">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ $biodata->no_hp ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                                        <input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" value="{{ $biodata->tahun_masuk ?? '' }}">
                                    </div>

                                    <!-- Tambahan kolom baru -->
                                    <div class="col-md-12 mb-3">
                                        <label for="judul_skripsi" class="form-label">Judul Skripsi</label>
                                        <input type="text" name="judul_skripsi" id="judul_skripsi" class="form-control" value="{{ $biodata->judul_skripsi ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lama_studi" class="form-label">Lama Studi</label>
                                        <input type="text" name="lama_studi" id="lama_studi" class="form-control" value="{{ $biodata->lama_studi ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lulus" class="form-label">Tanggal Lulus</label>
                                        <input type="date" name="tanggal_lulus" id="tanggal_lulus" class="form-control" value="{{ $biodata->tanggal_lulus ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nomor_ijazah" class="form-label">Nomor Ijazah</label>
                                        <input type="text" name="nomor_ijazah" id="nomor_ijazah" class="form-control" value="{{ $biodata->nomor_ijazah ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ipk" class="form-label">IPK</label>
                                        <input type="text" name="ipk" id="ipk" class="form-control" value="{{ $biodata->ipk ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
