@extends('layouts.app')

@section('title', 'SKPI Saya')

@section('content')
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Program Studi</h4>
                        <br>
                        <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahProdi">Tambah</button>

                        <!-- Modal Tambah Prodi -->
                        <div class="modal fade" id="modalTambahProdi" tabindex="-1" aria-labelledby="modalTambahProdiLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahProdiLabel">Tambah Program Studi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form action="{{ route('admin.fakultas.prodi.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                                    <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="kode_prodi" class="form-label">Kode Program Studi</label>
                                    <input type="text" name="kode_prodi" id="kode_prodi" class="form-control" required>
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
                        <!-- Modal Edit Prodi -->
                        <div class="modal fade" id="modalEditProdi" tabindex="-1" aria-labelledby="modalEditProdiLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalEditProdiLabel">Edit Program Studi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form id="formEditProdi" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="edit_nama_prodi" class="form-label">Nama Program Studi</label>
                                    <input type="text" name="nama_prodi" id="edit_nama_prodi" class="form-control" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="edit_kode_prodi" class="form-label">Kode Program Studi</label>
                                    <input type="text" name="kode_prodi" id="edit_kode_prodi" class="form-control" required>
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
                        <br>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                <table class="table table-hover mb-3">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Program Studi</th>
                                            <th>Kode Program Studi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prodis as $prodi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $prodi->nama_prodi ?? '-' }}</td>
                                                <td>{{ $prodi->kode_prodi ?? '-' }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning btn-edit-prodi" data-id="{{ $prodi->id }}">Edit</a>
                                                    <form action="{{ route('admin.fakultas.prodi.destroy', $prodi->id) }}" method="POST" style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada data program studi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-edit-prodi').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                fetch('/admin/fakultas/prodi/' + id + '/edit')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_nama_prodi').value = data.nama_prodi;
                        document.getElementById('edit_kode_prodi').value = data.kode_prodi;
                        document.getElementById('formEditProdi').action = '/admin/fakultas/prodi/' + id;
                        var modal = new bootstrap.Modal(document.getElementById('modalEditProdi'));
                        modal.show();
                    });
            });
        });
    });
</script>
@endpush