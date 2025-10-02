@extends('layouts.app')
@section('content')
<div class="container">
	<h2>Verifikasi Pengajuan SKPI Mahasiswa</h2>
	<table class="table table-bordered">
		<thead>
			<tr>
                <th>No. </th>
				<th>Nama Mahasiswa</th>
				<th>NIM</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			@foreach($mahasiswas as $mhs)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $mhs->biodataMahasiswa->nama ?? '-' }}</td>
				<td>{{ $mhs->biodataMahasiswa->nim ?? '-' }}</td>
				<td>
					<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPengajuan{{ $mhs->id }}">Lihat Pengajuan</button>
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
			<h5>Penghargaan/Prestasi</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Keterangan Indonesia</th>
						<th>Keterangan Inggris</th>
						<th>Jenis Organisasi/Lembaga</th>
						<th>Tahun</th>
						<th>Bukti</th>
						<th>Catatan</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->prestasi ?? [] as $item)
					<tr>
						<td>{{ $item->keterangan_indonesia }}</td>
						<td>{{ $item->keterangan_inggris }}</td>
						<td>{{ $item->jenis_organisasi }}</td>
						<td>{{ $item->tahun }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>{{ $item->catatan }}</td>
						<td>
					        <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="prestasi">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" title="Terima"><i class="bi bi-check-circle"></i> Terima</button>
                            </form>
                            <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="prestasi">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger" title="Tolak"><i class="bi bi-x-circle"></i> Tolak</button>
                            </form>
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
						<th>Bukti</th>
						<th>Catatan</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->organisasi ?? [] as $item)
					<tr>
						<td>{{ $item->organisasi }}</td>
						<td>{{ $item->tahun_awal }}</td>
						<td>{{ $item->tahun_akhir }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>{{ $item->catatan }}</td>
						<td>
					        <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="organisasi">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" title="Terima"><i class="bi bi-check-circle"></i> Terima</button>
                            </form>
                            <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="organisasi">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger" title="Tolak"><i class="bi bi-x-circle"></i> Tolak</button>
                            </form>
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Kompetensi Bahasa Internasional</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Nama Kompetensi</th>
						<th>Skor Kompetensi</th>
						<th>Tahun</th>
						<th>Bukti</th>
						<th>Catatan</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->kompetensiBahasa ?? [] as $item)
					<tr>
						<td>{{ $item->nama_kompetensi }}</td>
						<td>{{ $item->skor_kompetensi }}</td>
						<td>{{ $item->tahun }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>{{ $item->catatan }}</td>
						<td>
					        <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="bahasa">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" title="Terima"><i class="bi bi-check-circle"></i> Terima</button>
                            </form>
                            <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="bahasa">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger" title="Tolak"><i class="bi bi-x-circle"></i> Tolak</button>
                            </form>
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Pengalaman Magang</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Keterangan Indonesia</th>
						<th>Keterangan Inggris</th>
						<th>Lembaga/Institusi</th>
						<th>Tahun</th>
						<th>Bukti</th>
						<th>Catatan</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->magang ?? [] as $item)
					<tr>
						<td>{{ $item->keterangan_indonesia }}</td>
						<td>{{ $item->keterangan_inggris }}</td>
						<td>{{ $item->lembaga }}</td>
						<td>{{ $item->tahun }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>{{ $item->catatan }}</td>
						<td>
					        <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="magang">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" title="Terima"><i class="bi bi-check-circle"></i> Terima</button>
                            </form>
                            <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="magang">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger" title="Tolak"><i class="bi bi-x-circle"></i> Tolak</button>
                            </form>
				        </td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h5>Kompetensi Keagamaan</h5>
			<table class="table table-hover mb-3">
				<thead>
					<tr>
						<th>Keterangan Indonesia</th>
						<th>Keterangan Inggris</th>
						<th>Tahun</th>
						<th>Bukti</th>
						<th>Catatan</th>
                        <th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mhs->kompetensiKeagamaan ?? [] as $item)
					<tr>
						<td>{{ $item->keterangan_indonesia }}</td>
						<td>{{ $item->keterangan_inggris }}</td>
						<td>{{ $item->tahun }}</td>
						<td>@if($item->bukti)<a href="{{ asset('storage/'.$item->bukti) }}" target="_blank">Lihat</a>@endif</td>
						<td>{{ $item->catatan }}</td>
						<td>
					        <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="keagamaan">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="btn btn-success" title="Terima"><i class="bi bi-check-circle"></i> Terima</button>
                            </form>
                            <form action="{{ route('prodi.verifikasi.aksi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="keagamaan">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger" title="Tolak"><i class="bi bi-x-circle"></i> Tolak</button>
                            </form>
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
@endsection
