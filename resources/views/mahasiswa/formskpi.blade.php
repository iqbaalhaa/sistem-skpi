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
                        <h4 class="card-title">Keahlian Tambahan</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahKeahlianTambahan">Tambah</button>
        <!-- Modal Tambah Keahlian Tambahan -->
        <div class="modal fade" id="modalTambahKeahlianTambahan" tabindex="-1" aria-labelledby="modalTambahKeahlianTambahanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKeahlianTambahanLabel">Tambah Keahlian Tambahan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('keahlian-tambahan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Keahlian</label>
                                <input type="text" name="nama_keahlian" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lembaga</label>
                                <input type="text" name="lembaga" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti Sertifikat</label>
                                <input type="file" name="bukti" class="form-control">
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
                                                <th>Nama Keahlian</th>
                                                <th>Lembaga</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->keahlianTambahan as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->nama_keahlian }}</td>
                                                <td>{{ $item->lembaga ?? '-' }}</td>
                                                <td>{{ $item->tahun ?? '-' }}</td>
                                                <td>
                                                    @if($item->verifikasi == 1)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @elseif($item->verifikasi == 2)
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @else
                                                        <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailKeahlianTambahanModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editKeahlianTambahanModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                                    <form action="{{ route('keahlian-tambahan.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Modal Edit Keahlian Tambahan -->
                                            <div class="modal fade" id="editKeahlianTambahanModal{{ $item->id }}" tabindex="-1" aria-labelledby="editKeahlianTambahanModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editKeahlianTambahanModalLabel{{ $item->id }}">Edit Keahlian Tambahan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('keahlian-tambahan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Keahlian</label>
                                                                    <input type="text" name="nama_keahlian" class="form-control" value="{{ $item->nama_keahlian }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lembaga</label>
                                                                    <input type="text" name="lembaga" class="form-control" value="{{ $item->lembaga }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor Sertifikat</label>
                                                                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $item->nomor_sertifikat }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti Sertifikat (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
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
                                            <!-- Modal Detail Keahlian Tambahan -->
                                            <div class="modal fade" id="detailKeahlianTambahanModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailKeahlianTambahanModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailKeahlianTambahanModalLabel{{ $item->id }}">Detail Keahlian Tambahan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Nama Keahlian:</strong><br>
                                                                {{ $item->nama_keahlian }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Lembaga:</strong><br>
                                                                {{ $item->lembaga ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Tahun:</strong><br>
                                                                {{ $item->tahun ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Nomor Sertifikat:</strong><br>
                                                                {{ $item->nomor_sertifikat ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Bukti Sertifikat:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
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
                                <label class="form-label">Nama Penghargaan/Prestasi</label>
                                <input type="text" name="keterangan_indonesia" class="form-control" required>
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">Keterangan Inggris</label>
                                <input type="text" name="keterangan_inggris" class="form-control" required>
                            </div> -->
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
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
                            </div> -->
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
                                                <th>Nama Penghargaan/Prestasi</th>
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
                                                            @if($item->verifikasi == 1)
                                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                            @elseif($item->verifikasi == 2)
                                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                            @else
                                                                <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
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
                                                                    <label class="form-label">Nama Penghargaan/Prestasi</label>
                                                                    <input type="text" name="keterangan_indonesia" class="form-control" value="{{ $item->keterangan_indonesia }}" required>
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Keterangan Inggris</label>
                                                                    <input type="text" name="keterangan_inggris" class="form-control" value="{{ $item->keterangan_inggris }}" required>
                                                                </div> -->
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
                                                                    <label class="form-label">Nomor Sertifikat</label>
                                                                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $item->nomor_sertifikat ?? '' }}">
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div> -->
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
                                                            <!-- <div class="mb-2">
                                                                <strong>Keterangan Inggris:</strong><br>
                                                                {{ $item->keterangan_inggris }}
                                                            </div> -->
                                                            <div class="mb-2">
                                                                <strong>Bukti:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                            <!-- <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div> -->
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
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti</label>
                                <input type="file" name="bukti" class="form-control">
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
                            </div> -->
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
                                                    @if($item->verifikasi == 1)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @elseif($item->verifikasi == 2)
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @else
                                                        <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
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
                                                                    <label class="form-label">Nomor Sertifikat</label>
                                                                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $item->nomor_sertifikat ?? '' }}">
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div> -->
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
                                                            <!-- <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div> -->
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
                        <h4 class="card-title">Pengalaman Kerja/Magang</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahMagang">Tambah</button>
        <!-- Modal Tambah Pengalaman Magang -->
        <div class="modal fade" id="modalTambahMagang" tabindex="-1" aria-labelledby="modalTambahMagangLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahMagangLabel">Tambah Pengalaman Kerja/Magang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Pengalaman Kerja/Magang</label>
                                <input type="text" name="keterangan_indonesia" class="form-control" required>
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">Keterangan Inggris</label>
                                <input type="text" name="keterangan_inggris" class="form-control" required>
                            </div> -->
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
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control"></textarea>
                            </div> -->
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
                                                <th>Pengalaman Kerja/Magang</th>
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
                                                    @if($item->verifikasi == 1)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @elseif($item->verifikasi == 2)
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @else
                                                        <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
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
                                                            <h5 class="modal-title" id="editMagangModalLabel{{ $item->id }}">Edit Pengalaman Kerja/Magang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('magang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pengalaman Kerja/Magang</label>
                                                                    <input type="text" name="keterangan_indonesia" class="form-control" value="{{ $item->keterangan_indonesia }}" required>
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Keterangan Inggris</label>
                                                                    <input type="text" name="keterangan_inggris" class="form-control" value="{{ $item->keterangan_inggris }}" required>
                                                                </div> -->
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
                                                                    <label class="form-label">Nomor Sertifikat</label>
                                                                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $item->nomor_sertifikat ?? '' }}">
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label class="form-label">Catatan</label>
                                                                    <textarea name="catatan" class="form-control">{{ $item->catatan }}</textarea>
                                                                </div> -->
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
                                                            <!-- <div class="mb-2">
                                                                <strong>Catatan:</strong><br>
                                                                {{ $item->catatan ?? '-' }}
                                                            </div> -->
                                                            <!-- <div class="mb-2">
                                                                <strong>Keterangan Inggris:</strong><br>
                                                                {{ $item->keterangan_inggris ?? '-' }}
                                                            </div> -->
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
                        <h4 class="card-title">Kegiatan Lain-lain</h4>
                        <br>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahLainLain">Tambah</button>
        <!-- Modal Tambah Kegiatan Lain-lain -->
        <div class="modal fade" id="modalTambahLainLain" tabindex="-1" aria-labelledby="modalTambahLainLainLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLainLainLabel">Tambah Kegiatan Lain-lain</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('lain-lain.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti Sertifikat</label>
                                <input type="file" name="bukti" class="form-control">
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
                                                <th>Nama Kegiatan</th>
                                                <th>Penyelenggara</th>
                                                <th>Tahun</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($mhs->lainLain as $i => $item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->nama_kegiatan }}</td>
                                                <td>{{ $item->penyelenggara ?? '-' }}</td>
                                                <td>{{ $item->tahun ?? '-' }}</td>
                                                <td>
                                                    @if($item->verifikasi == 1)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                                    @elseif($item->verifikasi == 2)
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                                    @else
                                                        <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailLainLainModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                                    <button class="btn btn-sm btn-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editLainLainModal{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                                    <form action="{{ route('lain-lain.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Modal Edit Kegiatan Lain-lain -->
                                            <div class="modal fade" id="editLainLainModal{{ $item->id }}" tabindex="-1" aria-labelledby="editLainLainModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editLainLainModalLabel{{ $item->id }}">Edit Kegiatan Lain-lain</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('lain-lain.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Kegiatan</label>
                                                                    <input type="text" name="nama_kegiatan" class="form-control" value="{{ $item->nama_kegiatan }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Penyelenggara</label>
                                                                    <input type="text" name="penyelenggara" class="form-control" value="{{ $item->penyelenggara }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label>
                                                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="{{ $item->tahun }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor Sertifikat</label>
                                                                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $item->nomor_sertifikat }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti Sertifikat (kosongkan jika tidak diubah)</label>
                                                                    <input type="file" name="bukti" class="form-control">
                                                                    @if($item->bukti)
                                                                        <small class="text-muted">Bukti saat ini: <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a></small>
                                                                    @endif
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
                                            <!-- Modal Detail Kegiatan Lain-lain -->
                                            <div class="modal fade" id="detailLainLainModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailLainLainModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailLainLainModalLabel{{ $item->id }}">Detail Kegiatan Lain-lain</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-2">
                                                                <strong>Nama Kegiatan:</strong><br>
                                                                {{ $item->nama_kegiatan }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Penyelenggara:</strong><br>
                                                                {{ $item->penyelenggara ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Tahun:</strong><br>
                                                                {{ $item->tahun ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Nomor Sertifikat:</strong><br>
                                                                {{ $item->nomor_sertifikat ?? '-' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Bukti Sertifikat:</strong><br>
                                                                @if($item->bukti)
                                                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                                                                @else
                                                                    -
                                                                @endif
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
