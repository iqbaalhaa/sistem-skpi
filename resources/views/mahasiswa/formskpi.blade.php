@extends('layouts.app')

@section('title', 'SKPI Saya')

@section('content')
@php
    // Tidak perlu inisialisasi variabel kosong, data diambil dari relasi $mahasiswa
@endphp
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Penghargaan/Prestasi</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahPenghargaan">Tambah</button>
        <!-- Modal Tambah Penghargaan/Prestasi -->
        <div class="modal fade" id="modalTambahPenghargaan" tabindex="-1" aria-labelledby="modalTambahPenghargaanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahPenghargaanLabel">Tambah Penghargaan/Prestasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('penghargaan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Indonesia</label>
                                <input type="text" name="keterangan_indonesia" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan Inggris</label>
                                <input type="text" name="keterangan_inggris" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Organisasi/Lembaga</label>
                                <input type="text" name="jenis_organisasi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
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
                        <br>
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @foreach ($mahasiswa as $mhs)
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Keterangan Indonesia</th>
                                                <th>Jenis Organisasi/lembaga</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->prestasi as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->keterangan_indonesia }}</td>
                                                <td>{{ $item->jenis_organisasi }}</td>
                                                <td>{{ $item->tahun }}</td>
                                                <td>
                                                    @if($item->verifikasi)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailPrestasiModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editPrestasiModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                                    <form action="{{ route('penghargaan.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            <!-- Modal Edit Penghargaan/Prestasi -->
                                            <div class="modal fade" id="editPrestasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="editPrestasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editPrestasiModalLabel{{ $item->id }}">Edit Penghargaan/Prestasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('penghargaan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Indonesia</label>
                                                                    <input type="text" name="keterangan_indonesia" class="form-control" value="{{ $item->keterangan_indonesia }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Inggris</label>
                                                                    <input type="text" name="keterangan_inggris" class="form-control" value="{{ $item->keterangan_inggris }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Jenis Organisasi/Lembaga</label>
                                                                    <input type="text" name="jenis_organisasi" class="form-control" value="{{ $item->jenis_organisasi }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            </tr>
                                            <!-- Modal Detail Penghargaan/Prestasi -->
                                            <div class="modal fade" id="detailPrestasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailPrestasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailPrestasiModalLabel{{ $item->id }}">Detail Penghargaan/Prestasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Keterangan Inggris:</strong><br>
                                                                {{ $item->keterangan_inggris }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pengalaman Berorganisasi</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahOrganisasi">Tambah</button>
        <!-- Modal Tambah Pengalaman Berorganisasi -->
        <div class="modal fade" id="modalTambahOrganisasi" tabindex="-1" aria-labelledby="modalTambahOrganisasiLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahOrganisasiLabel">Tambah Pengalaman Berorganisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('organisasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Organisasi</label>
                                <input type="text" name="organisasi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun Awal</label>
                                <input type="number" name="tahun_awal" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun Akhir</label>
                                <input type="number" name="tahun_akhir" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
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
                        <br>
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @foreach ($mahasiswa as $mhs)
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Organisasi</th>
                                                <th>Tahun Awal</th>
                                                <th>Tahun Akhir</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->organisasi as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->organisasi }}</td>
                                                <td>{{ $item->tahun_awal }}</td>
                                                <td>{{ $item->tahun_akhir }}</td>
                                                <td>
                                                    @if($item->verifikasi)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailOrganisasiModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editOrganisasiModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                            <!-- Modal Edit Pengalaman Organisasi -->
                                            <div class="modal fade" id="editOrganisasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="editOrganisasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editOrganisasiModalLabel{{ $item->id }}">Edit Pengalaman Berorganisasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('organisasi.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Organisasi</label>
                                                                    <input type="text" name="organisasi" class="form-control" value="{{ $item->organisasi }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun Awal</label>
                                                                    <input type="number" name="tahun_awal" class="form-control" min="1900" max="2100" value="{{ $item->tahun_awal }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun Akhir</label>
                                                                    <input type="number" name="tahun_akhir" class="form-control" min="1900" max="2100" value="{{ $item->tahun_akhir }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                                    <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Modal Detail Organisasi -->
                                            <div class="modal fade" id="detailOrganisasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailOrganisasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailOrganisasiModalLabel{{ $item->id }}">Detail Pengalaman Berorganisasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kompetensi Berkomunikasi Bahasa Internasional</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahBahasa">Tambah</button>
        <!-- Modal Tambah Kompetensi Bahasa -->
        <div class="modal fade" id="modalTambahBahasa" tabindex="-1" aria-labelledby="modalTambahBahasaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahBahasaLabel">Tambah Kompetensi Berkomunikasi Bahasa Internasional</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('bahasa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Kompetensi</label>
                                <input type="text" name="nama_kompetensi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Skor Kompetensi</label>
                                <input type="text" name="skor_kompetensi" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
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
                        <br>
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @foreach ($mahasiswa as $mhs)
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Kompetensi</th>
                                                <th>Skor Kompetensi</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->kompetensiBahasa as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->nama_kompetensi }}</td>
                                                <td>{{ $item->skor_kompetensi }}</td>
                                                <td>{{ $item->tahun }}</td>
                                                <td>
                                                    @if($item->verifikasi)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailBahasaModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editBahasaModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                            <!-- Modal Edit Kompetensi Bahasa -->
                                            <div class="modal fade" id="editBahasaModal{{ $item->id }}" tabindex="-1" aria-labelledby="editBahasaModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editBahasaModalLabel{{ $item->id }}">Edit Kompetensi Berkomunikasi Bahasa Internasional</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('bahasa.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Kompetensi</label>
                                                                    <input type="text" name="nama_kompetensi" class="form-control" value="{{ $item->nama_kompetensi }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Skor Kompetensi</label>
                                                                    <input type="text" name="skor_kompetensi" class="form-control" value="{{ $item->skor_kompetensi }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                                    <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Modal Detail Bahasa -->
                                            <div class="modal fade" id="detailBahasaModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailBahasaModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailBahasaModalLabel{{ $item->id }}">Detail Kompetensi Bahasa</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pengalaman Magang</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahMagang">Tambah</button>
        <!-- Modal Tambah Pengalaman Magang -->
        <div class="modal fade" id="modalTambahMagang" tabindex="-1" aria-labelledby="modalTambahMagangLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahMagangLabel">Tambah Pengalaman Magang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Indonesia</label>
                                <input type="text" name="keterangan_indonesia" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan Inggris</label>
                                <input type="text" name="keterangan_inggris" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lembaga/Institusi</label>
                                <input type="text" name="lembaga" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
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
                        <br>
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @foreach ($mahasiswa as $mhs)
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Keterangan Indonesia</th>
                                                <th>Lembaga/Insitusi</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->magang as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->keterangan_indonesia }}</td>
                                                <td>{{ $item->lembaga }}</td>
                                                <td>{{ $item->tahun }}</td>
                                                <td>
                                                    @if($item->verifikasi)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailMagangModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editMagangModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                            <!-- Modal Edit Pengalaman Magang -->
                                            <div class="modal fade" id="editMagangModal{{ $item->id }}" tabindex="-1" aria-labelledby="editMagangModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editMagangModalLabel{{ $item->id }}">Edit Pengalaman Magang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('magang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Indonesia</label>
                                                                    <input type="text" name="keterangan_indonesia" class="form-control" value="{{ $item->keterangan_indonesia }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Inggris</label>
                                                                    <input type="text" name="keterangan_inggris" class="form-control" value="{{ $item->keterangan_inggris }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lembaga/Institusi</label>
                                                                    <input type="text" name="lembaga" class="form-control" value="{{ $item->lembaga }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                                    <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Modal Detail Magang -->
                                            <div class="modal fade" id="detailMagangModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailMagangModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailMagangModalLabel{{ $item->id }}">Detail Pengalaman Magang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Keterangan Inggris:</strong><br>
                                                                {{ $item->keterangan_inggris ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kompetensi Keagamaan</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahKeagamaan">Tambah</button>
        <!-- Modal Tambah Kompetensi Keagamaan -->
        <div class="modal fade" id="modalTambahKeagamaan" tabindex="-1" aria-labelledby="modalTambahKeagamaanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKeagamaanLabel">Tambah Kompetensi Keagamaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('keagamaan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Indonesia</label>
                                <input type="text" name="keterangan_indonesia" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan Inggris</label>
                                <input type="text" name="keterangan_inggris" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
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
                        <br>
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @foreach ($mahasiswa as $mhs)
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Keterangan Indonesia</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->kompetensiKeagamaan as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->keterangan_indonesia }}</td>
                                                <td>{{ $item->tahun }}</td>
                                                <td>
                                                    @if($item->verifikasi)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailKeagamaanModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editKeagamaanModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                            <!-- Modal Edit Kompetensi Keagamaan -->
                                            <div class="modal fade" id="editKeagamaanModal{{ $item->id }}" tabindex="-1" aria-labelledby="editKeagamaanModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editKeagamaanModalLabel{{ $item->id }}">Edit Kompetensi Keagamaan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('keagamaan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Indonesia</label>
                                                                    <input type="text" name="keterangan_indonesia" class="form-control" value="{{ $item->keterangan_indonesia }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Keterangan Inggris</label>
                                                                    <input type="text" name="keterangan_inggris" class="form-control" value="{{ $item->keterangan_inggris }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                                    <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Modal Detail Keagamaan -->
                                            <div class="modal fade" id="detailKeagamaanModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailKeagamaanModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailKeagamaanModalLabel{{ $item->id }}">Detail Kompetensi Keagamaan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Keterangan Inggris:</strong><br>
                                                                {{ $item->keterangan_inggris ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
