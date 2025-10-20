@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
	<h2>Verifikasi Pengajuan SKPI Mahasiswa</h2>
	<table class="table table-bordered">
		<thead>
			<tr>
                <th>No. </th>
				<th>Nama Mahasiswa</th>
				<th>NIM</th>
                <th>Progress Verifikasi</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			@foreach($mahasiswas as $mhs)
            @php
                $total = 0;
                $verified = 0;
                
                // Count prestasi
                if($mhs->prestasi && count($mhs->prestasi) > 0) {
                    $total++;
                    if($mhs->prestasi->contains('verifikasi', 1)) $verified++;
                }
                
                // Count organisasi
                if($mhs->organisasi && count($mhs->organisasi) > 0) {
                    $total++;
                    if($mhs->organisasi->contains('verifikasi', 1)) $verified++;
                }
                
                // Count magang
                if($mhs->magang && count($mhs->magang) > 0) {
                    $total++;
                    if($mhs->magang->contains('verifikasi', 1)) $verified++;
                }
                
                // Count keahlian tambahan
                if($mhs->keahlianTambahan && count($mhs->keahlianTambahan) > 0) {
                    $total++;
                    if($mhs->keahlianTambahan->contains('verifikasi', 1)) $verified++;
                }
                
                // Count lain lain
                if($mhs->lainLain && count($mhs->lainLain) > 0) {
                    $total++;
                    if($mhs->lainLain->contains('verifikasi', 1)) $verified++;
                }
                
                $progress = $total > 0 ? ($verified / $total) * 100 : 0;
                $allApproved = $total > 0 && $verified === $total;
                // Cek status pengajuan SKPI terbaru (menggunakan relasi yang sudah di-eager load)
                $latestStatus = optional($mhs->pengajuanSkpi->sortByDesc('created_at')->first())->status;
                $alreadyAccepted = in_array($latestStatus, ['diterima_prodi', 'diterima_fakultas']);
            @endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $mhs->biodataMahasiswa->nama ?? '-' }}</td>
				<td>{{ $mhs->biodataMahasiswa->nim ?? '-' }}</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ number_format($progress, 0) }}%</div>
                    </div>
                </td>
				<td>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPengajuan{{ $mhs->id }}">Lihat Pengajuan</button>
                    @if($alreadyAccepted)
                        <button class="btn btn-secondary" disabled>Diterima</button>
                    @elseif($allApproved)
                        <button class="btn btn-success" onclick="terimaMahasiswa('{{ $mhs->id }}', this)">Terima</button>
                    @endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	@foreach($mahasiswas as $mhs)
	<!-- Modal -->
	<div class="modal fade" id="modalPengajuan{{ $mhs->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $mhs->id }}" aria-hidden="true">
	  <div class="modal-dialog modal-xl">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="modalLabel{{ $mhs->id }}">Pengajuan SKPI - {{ $mhs->biodataMahasiswa->nama ?? '-' }} ({{ $mhs->biodataMahasiswa->nim ?? '-' }})</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">

            <h5>Keahlian Tambahan</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Nama Keahlian</th>
						<th>Lembaga</th>
						<th>Tahun</th>
						<th>Nomor Sertifikat</th>
						<th>Bukti Sertifikat</th>
						<th>Status Verifikasi</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->keahlianTambahan ?? [] as $item)
					<tr>
						<td>{{ $item->nama_keahlian }}</td>
						<td>{{ $item->lembaga ?? '-' }}</td>
						<td>{{ $item->tahun ?? '-' }}</td>
						<td>{{ $item->nomor_sertifikat ?? '-' }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>
                            @if($item->verifikasi === 1)
                                <span class="badge bg-success">Diterima</span>
                            @elseif($item->verifikasi === 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
						<td>
                                    @if($item->verifikasi === 1)
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'keahlian-tambahan', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                                    @elseif($item->verifikasi === 2)
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'keahlian-tambahan', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                    @else
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'keahlian-tambahan', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'keahlian-tambahan', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                                    @endif
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Penghargaan/Prestasi</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
                        <th>Nama Penghargaan/Prestasi</th>
                        <th>Jenis Organisasi/Lembaga</th>
                        <th>Tahun</th>
                        <th>Nomor Sertifikat</th>
                        <th>Bukti</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->prestasi ?? [] as $item)
					<tr>
						<td>{{ $item->keterangan_indonesia }}</td>
						<td>{{ $item->jenis_organisasi }}</td>
						<td>{{ $item->tahun }}</td>
        				<td>{{ $item->nomor_sertifikat ?? '-' }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
        				<td>
        					@if($item->verifikasi === 1)
        						<span class="badge bg-success">Diterima</span>
        					@elseif($item->verifikasi === 2)
        						<span class="badge bg-danger">Ditolak</span>
        					@else
        						<span class="badge bg-secondary">Pending</span>
        					@endif
        				</td>
						<td>
                            @if($item->verifikasi === 1)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'prestasi', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @elseif($item->verifikasi === 2)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'prestasi', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                            @else
                                <button onclick="verifikasiItem('{{ $item->id }}', 'prestasi', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                <button onclick="verifikasiItem('{{ $item->id }}', 'prestasi', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @endif
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Pengalaman Berorganisasi</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Organisasi</th>
						<th>Tahun Awal</th>
						<th>Tahun Akhir</th>
						<th>Nomor Sertifikat</th>
						<th>Bukti</th>
						<th>Status Verifikasi</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->organisasi ?? [] as $item)
					<tr>
                        <td>{{ $item->organisasi }}</td>
                        <td>{{ $item->tahun_awal }}</td>
                        <td>{{ $item->tahun_akhir }}</td>
                        <td>{{ $item->nomor_sertifikat ?? '-' }}</td>
                        <td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
                        <td>
                            @if($item->verifikasi === 1)
                                <span class="badge bg-success">Diterima</span>
                            @elseif($item->verifikasi === 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($item->verifikasi === 1)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'organisasi', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @elseif($item->verifikasi === 2)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'organisasi', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                            @else
                                <button onclick="verifikasiItem('{{ $item->id }}', 'organisasi', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                <button onclick="verifikasiItem('{{ $item->id }}', 'organisasi', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @endif
                        </td>
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Pengalaman Kerja/Magang</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Pengalaman Kerja/Magang</th>
						<th>Lembaga/Institusi</th>
						<th>Tahun</th>
						<th>Nomor Sertifikat</th>
						<th>Bukti</th>
						<th>Status Verifikasi</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->magang ?? [] as $item)
					<tr>
                        <td>{{ $item->keterangan_indonesia }}</td>
                        <td>{{ $item->lembaga }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->nomor_sertifikat ?? '-' }}</td>
                        <td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
                        <td>
                            @if($item->verifikasi === 1)
                                <span class="badge bg-success">Diterima</span>
                            @elseif($item->verifikasi === 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($item->verifikasi === 1)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'magang', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @elseif($item->verifikasi === 2)
                                <button onclick="verifikasiItem('{{ $item->id }}', 'magang', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                            @else
                                <button onclick="verifikasiItem('{{ $item->id }}', 'magang', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                <button onclick="verifikasiItem('{{ $item->id }}', 'magang', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                            @endif
                        </td>
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Kegiatan Lain-lain</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Nama Kegiatan</th>
						<th>Penyelenggara</th>
						<th>Tahun</th>
						<th>Nomor Sertifikat</th>
						<th>Bukti Sertifikat</th>
						<th>Status Verifikasi</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->lainLain ?? [] as $item)
					<tr>
						<td>{{ $item->nama_kegiatan }}</td>
						<td>{{ $item->penyelenggara ?? '-' }}</td>
						<td>{{ $item->tahun ?? '-' }}</td>
						<td>{{ $item->nomor_sertifikat ?? '-' }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>
                            @if($item->verifikasi === 1)
                                <span class="badge bg-success">Diterima</span>
                            @elseif($item->verifikasi === 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
						<td>
                                    @if($item->verifikasi === 1)
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'lain-lain', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                                    @elseif($item->verifikasi === 2)
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'lain-lain', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                    @else
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'lain-lain', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                                        <button onclick="verifikasiItem('{{ $item->id }}', 'lain-lain', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                                    @endif
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>
		  </div>
		</div>
	  </div>
	</div>
	@endforeach
</div>
@push('scripts')
<script>
function verifikasiItem(id, type, status, button) {
    console.log('verifikasiItem called:', {id, type, status});
    // Disable the buttons first
    const parentTd = button.closest('td');
    const buttons = parentTd.querySelectorAll('button');
    buttons.forEach(btn => btn.disabled = true);

    fetch(`/prodi/verifikasi/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            type: type,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response received:', data);
        if(data.success) {
            const statusTd = parentTd.previousElementSibling;
            
            // Determine the correct status display based on table type
            if (type === 'keahlian-tambahan' || type === 'lain-lain') {
                // New tables: 0=pending, 1=accepted, 2=rejected
                if (status === 1) {
                    statusTd.innerHTML = '<span class="badge bg-success">Diterima</span>';
                    parentTd.innerHTML = `<button onclick="verifikasiItem('${id}', '${type}', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>`;
                } else if (status === 2) {
                    statusTd.innerHTML = '<span class="badge bg-danger">Ditolak</span>';
                    parentTd.innerHTML = `
                        <button onclick="verifikasiItem('${id}', '${type}', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                        <button onclick="verifikasiItem('${id}', '${type}', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                    `;
                } else if (status === 0) {
                    statusTd.innerHTML = '<span class="badge bg-secondary">Pending</span>';
                    parentTd.innerHTML = `
                        <button onclick="verifikasiItem('${id}', '${type}', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                        <button onclick="verifikasiItem('${id}', '${type}', 2, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                    `;
                }
            } else {
                // Old tables: 0=rejected, 1=accepted
                statusTd.innerHTML = status === 1 
                    ? '<span class="badge bg-success">Diterima</span>'
                    : '<span class="badge bg-danger">Ditolak</span>';
                
                if (status === 1) {
                    // Keep only the reject button
                    parentTd.innerHTML = `<button onclick="verifikasiItem('${id}', '${type}', 0, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>`;
                } else {
                    // Show both buttons when rejected
                    parentTd.innerHTML = `
                        <button onclick="verifikasiItem('${id}', '${type}', 1, this)" class="btn btn-success btn-sm" title="Terima"><i class="bi bi-check-circle"></i></button>
                        <button onclick="verifikasiItem('${id}', '${type}', 0, this)" class="btn btn-danger btn-sm" title="Tolak"><i class="bi bi-x-circle"></i></button>
                    `;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Re-enable the buttons if there's an error
        buttons.forEach(btn => btn.disabled = false);
        alert('Terjadi kesalahan saat memproses verifikasi. Silakan coba lagi.');
    });
}

function terimaMahasiswa(userId, button) {
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = 'Memproses...';

    fetch(`/prodi/terima-mahasiswa/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json().then(data => ({ ok: response.ok, status: response.status, body: data })))
    .then(({ ok, status, body }) => {
        if (ok && body.success) {
            button.classList.remove('btn-success');
            button.classList.add('btn-secondary');
            button.innerHTML = 'Sudah Diterima';
            button.disabled = true;
        } else {
            alert(body.message || 'Gagal memproses.');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan jaringan.');
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

// Add this at page load to ensure CSRF token is set
document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
});
</script>
@endpush
@endsection
