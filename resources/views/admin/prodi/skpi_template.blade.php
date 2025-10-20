<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SKPI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 2cm;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 15px;
        }
        .photo {
            float: right;
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            margin-left: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .section {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT KETERANGAN PENDAMPING IJAZAH</h2>
        <h3>{{ $nomorSurat }}</h3>
    </div>

    <div class="content">
        @if($biodata->foto && Storage::exists('public/foto_mahasiswa/' . $biodata->foto))
            <img class="photo" src="{{ storage_path('app/public/foto_mahasiswa/' . $biodata->foto) }}" alt="Foto Mahasiswa">
        @endif

        <table>
            <tr>
                <td width="200">Nama Lengkap</td>
                <td>: {{ $biodata->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: {{ $biodata->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: {{ optional($user->prodi)->nama_prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Akreditasi</td>
                <td>: {{ optional($user->prodi)->akreditasi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenjang Pendidikan</td>
                <td>: {{ optional($user->prodi)->jenjang_pendidikan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Gelar</td>
                <td>: {{ optional($user->prodi)->gelar ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: {{ $biodata->tempat_lahir . ', ' . \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y') }}</td>
            </tr>
        </table>

        <div class="section">
            <h3>Penghargaan dan Prestasi</h3>
            @if($tables['prestasi']->isNotEmpty())
                <ol>
                    @foreach($tables['prestasi'] as $item)
                        <li>{{ $item->keterangan_indonesia }} ({{ $item->nomor_sertifikat }})</li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="section">
            <h3>Pengalaman Organisasi</h3>
            @if($tables['organisasi']->isNotEmpty())
                <ol>
                    @foreach($tables['organisasi'] as $item)
                        <li>{{ $item->organisasi }} ({{ $item->nomor_sertifikat }})</li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="section">
            <h3>Pengalaman Magang</h3>
            @if($tables['magang']->isNotEmpty())
                <ol>
                    @foreach($tables['magang'] as $item)
                        <li>{{ $item->keterangan_indonesia }} ({{ $item->nomor_sertifikat }})</li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="section">
            <h3>Keahlian Tambahan</h3>
            @if($tables['keahlian']->isNotEmpty())
                <ol>
                    @foreach($tables['keahlian'] as $item)
                        <li>{{ $item->nama_keahlian }} ({{ $item->nomor_sertifikat }})</li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="section">
            <h3>Kegiatan Lain-lain</h3>
            @if($tables['lain']->isNotEmpty())
                <ol>
                    @foreach($tables['lain'] as $item)
                        <li>{{ $item->nama_kegiatan }} ({{ $item->nomor_sertifikat }})</li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="footer">
            <p>Yogyakarta, {{ $tanggalCetak }}</p>
            <br><br><br>
            <p>Dekan</p>
        </div>
    </div>
</body>
</html>