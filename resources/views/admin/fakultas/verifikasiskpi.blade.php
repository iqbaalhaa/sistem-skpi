@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Verifikasi SKPI</h2>
            <p class="text-secondary">Pilih program studi untuk melihat pengajuan SKPI</p>
        </div>

        <div class="row justify-content-center">
            @forelse($prodi as $item)
                <div class="col-md-4 col-lg-4 mb-4">
                    <div class="card card-hover shadow-lg border-0 text-center h-100"
                        style="border-radius: 15px; cursor: pointer; transition: all 0.25s ease;"
                        onclick="loadMahasiswa({{ $item->id }}, '{{ $item->nama_prodi }}')">

                        {{-- Badge jumlah SKPI siap tanda tangan --}}
                        @if($item->pending_ttd > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow">
                                {{ $item->pending_ttd }}
                            </span>
                        @endif

                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3 icon-wrap">
                                <i class="bi bi-mortarboard-fill fs-1"></i>
                            </div>
                            <h5 class="fw-bold card-title mb-1">{{ $item->nama_prodi }}</h5>
                            <p class="text-muted medium mb-2">
                                {{ $item->jenjang_pendidikan ?? '-' }} {{ $item->gelar ? '(' . $item->gelar . ')' : '' }}
                            </p>
                            <span class="badge bg-info">{{ $item->akreditasi ?? 'Belum Akreditasi' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data Program Studi</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL FULLSCREEN --}}
<!-- ========== MODAL FULLSCREEN: Daftar Mahasiswa per Prodi ========== -->
<div class="modal fade" id="modalMahasiswa" tabindex="-1" aria-labelledby="modalMahasiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-full modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalMahasiswaLabel">Daftar Mahasiswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <h4 id="namaProdiTitle" class="fw-bold text-center mb-3"></h4>

        <div id="alert-area"></div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle" id="tabelMahasiswaModal">
            <thead class="table-light">
              <tr>
                <th style="width:5%">#</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Status</th>
                <th>Tanggal Diterima Prodi</th>
                <th style="width:18%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- isi lewat JS -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * loadMahasiswa(prodiId, namaProdi)
 * - memanggil endpoint JSON untuk mengambil pengajuan SKPI yang diterima_prodi
 * - menampilkan hasil ke dalam modal
 */
function loadMahasiswa(prodiId, namaProdi) {
    // show modal
    const modalEl = document.getElementById('modalMahasiswa');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // judul modal
    document.getElementById('namaProdiTitle').textContent = namaProdi;

    // loader sementara
    const tbody = document.querySelector('#tabelMahasiswaModal tbody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Memuat...</td></tr>';
    document.getElementById('alert-area').innerHTML = '';

    // fetch data JSON
    fetch(`/fakultas/prodi/${prodiId}/mahasiswa`)
      .then(res => {
        if(!res.ok) throw new Error('Gagal memuat data');
        return res.json();
      })
      .then(data => {
        if (!Array.isArray(data) || data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada mahasiswa diterima prodi</td></tr>';
          return;
        }

        let html = '';
        data.forEach((item, idx) => {
          // akses aman ke relasi biodata mahasiswa
          const biodata = item.user && (item.user.biodata_mahasiswa || item.user.biodataMahasiswa) ? (item.user.biodata_mahasiswa || item.user.biodataMahasiswa) : null;
          const nama = biodata?.nama ?? '-';
          const nim = biodata?.nim ?? '-';
          const tanggal = item.tanggal_verifikasi_prodi ?? '-';
          const statusBadge = `<span class="badge bg-success">${item.status}</span>`;

          html += `<tr>
              <td>${idx + 1}</td>
              <td>${escapeHtml(nama)}</td>
              <td>${escapeHtml(nim)}</td>
              <td>${statusBadge}</td>
              <td>${escapeHtml(tanggal)}</td>
              <td>
                <button class="btn btn-primary btn-sm" onclick="confirmTandaTangan(${item.id}, '${escapeJs(nama)}')">
                  <i class="bi bi-pencil-square"></i> Tanda Tangan
                </button>
              </td>
            </tr>`;
        });

        tbody.innerHTML = html;
      })
      .catch(err => {
        document.getElementById('alert-area').innerHTML = `<div class="alert alert-danger">Terjadi kesalahan: ${err.message}</div>`;
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Gagal memuat data.</td></tr>';
      });
}

/**
 * confirmTandaTangan(id, nama)
 * - tampilkan konfirmasi, lalu panggil tandaTangan()
 */
function confirmTandaTangan(id, nama) {
    if (!confirm(`Tanda tangani SKPI untuk ${nama} ?`)) return;
    tandaTangan(id);
}

/**
 * tandaTangan(id)
 * - panggil endpoint POST; perlu CSRF token
 */
function tandaTangan(id) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/fakultas/tandatangan/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            // tampil notif sederhana, dan reload table (modal)
            alert(json.message || 'Berhasil');
            // reload isi tabel: butuh prodiId. kita dapatkan dari judul modal (untuk reload, panggil ulang loadMahasiswa)
            // mencari prodiId dari judul tidak reliable — jadi lebih baik reload manual: tutup modal dan reload
            // tapi kita cukup refresh current modal data by triggering click ulang pada prodi (lebih kompleks)
            // simplest: refresh halaman kecil: reload modal content using currently visible prodiTitle
            // asumsi: nama prodi unik -> find its id is complex; untuk aman: tutup modal lalu reload modal list
            // sekarang: tutup modal
            const bsModal = bootstrap.Modal.getInstance(document.getElementById('modalMahasiswa'));
            if (bsModal) bsModal.hide();
            // opsional: reload halaman agar tombol ter-update (atau panggil ulang fungsi loadMahasiswa dengan prodiId jika tersedia)
            location.reload();
        } else {
            alert(json.message || 'Gagal menandatangani SKPI');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan jaringan');
        console.error(err);
    });
}

/* helper to escape HTML to prevent XSS in dynamic strings */
function escapeHtml(str = '') {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
}

/* helper to escape JS string used inside single-quoted onclick parameters */
function escapeJs(str = '') {
    return String(str).replace(/'/g, "\\'");
}
</script>
@endpush
