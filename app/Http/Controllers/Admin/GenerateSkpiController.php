<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;

use Carbon\Carbon;
use App\Models\BiodataMahasiswa;
use App\Models\User;
use App\Models\SkpiCertificate;
use App\Models\PenghargaanPrestasi;
use App\Models\PengalamanOrganisasi;
use App\Models\PengalamanMagang;
use App\Models\KeahlianTambahan;
use App\Models\LainLain;

class GenerateSkpiController extends Controller
{
    public function index()
    {
        $adminProdiId = Auth::user()->prodi_id;

        // Ambil mahasiswa berdasarkan biodata_mahasiswa.prodi_id (prioritas biodata),
        // dan pastikan user berperan sebagai mahasiswa
        $mahasiswa = BiodataMahasiswa::with(['user.pengajuanSkpi' => function($query) {
                $query->latest('created_at');
            }])
            ->where('prodi_id', $adminProdiId)
            ->whereHas('user', function ($q) {
                $q->where('role', 'mahasiswa');
            })
            ->get();

        return view('admin.prodi.generateskpi', compact('mahasiswa'));
    }

    public function generate(Request $request, int $userId)
    {
        $adminProdiId = Auth::user()->prodi_id;

        $user = User::with(['biodataMahasiswa', 'prodi'])
            ->where('role', 'mahasiswa')
            ->findOrFail($userId);

        $userProdiId = optional($user->biodataMahasiswa)->prodi_id ?? $user->prodi_id;
        if ($userProdiId !== $adminProdiId) {
            abort(403, 'Anda tidak memiliki akses untuk mahasiswa prodi lain.');
        }

        // === Template path ===
        $templatePaths = [
            storage_path('app/templates/skpi_template.docx'),
            public_path('storage/app/templates/skpi_template.docx'),
            public_path('templates/skpi_template.docx'),
        ];

        $templatePath = null;
        foreach ($templatePaths as $path) {
            if (file_exists($path)) {
                $templatePath = $path;
                break;
            }
        }

        if (!$templatePath) {
            $searchedPaths = implode("\n- ", $templatePaths);
            return back()->with('error', "Template SKPI tidak ditemukan. Telah dicari di:\n- " . $searchedPaths);
        }

        $biodata = $user->biodataMahasiswa;
        $processor = new TemplateProcessor($templatePath);

        // ==== FOTO MAHASISWA ====
        if ($biodata->foto && Storage::exists('public/foto_mahasiswa/' . $biodata->foto)) {
            $fotoPath = storage_path('app/public/foto_mahasiswa/' . $biodata->foto);
            $processor->setImageValue('foto_mahasiswa', [
                'path' => $fotoPath,
                'width' => 110,
                'height' => 130,
                'ratio' => true
            ]);
        } else {
            $processor->setValue('foto_mahasiswa', '(Foto tidak tersedia)');
        }

        // ==== PENOMORAN SURAT ====
        $lastNumber = SkpiCertificate::max('id') ?? 0;
        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $bulan = strtoupper(Carbon::now()->format('m'));
        $tahun = Carbon::now()->year;
        $nomorSurat = "B.$nextNumber/Un.09/VIII.I/PP.01.1/$bulan/$tahun";
        $processor->setValue('nomor_surat', $nomorSurat);

        // ==== ISI DATA DASAR ====
        $processor->setValue('nama_lengkap', $biodata->nama ?? '-');
        $processor->setValue('nim', $biodata->nim ?? '-');
        $processor->setValue('prodi', optional($user->prodi)->nama_prodi ?? '-');
        $processor->setValue('akreditasi', optional($user->prodi)->akreditasi ?? '-');
        $processor->setValue('jenjang_pendidikan', optional($user->prodi)->jenjang_pendidikan ?? '-');
        $processor->setValue('gelar', optional($user->prodi)->gelar ?? '-');
        $processor->setValue('ttl', $biodata->tempat_lahir . ', ' . Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y'));
        $processor->setValue('tahun_masuk', $biodata->tahun_masuk ?? '-');
        $processor->setValue('tanggal_lulus', $biodata->tanggal_lulus ?? '-');
        $processor->setValue('nomor_ijazah', $biodata->nomor_ijazah ?? '-');
        $processor->setValue('judul_skripsi', $biodata->judul_skripsi ?? '-');
        $processor->setValue('ipk', $biodata->ipk ?? '-');
        $processor->setValue('lama_studi', $biodata->lama_studi ?? '-');
        $processor->setValue('tanggal_cetak', Carbon::now()->translatedFormat('d F Y'));

        // ==== DATA TABEL ====
        $tables = [
            'prestasi' => PenghargaanPrestasi::where('user_id', $user->id)->get(),
            'organisasi' => PengalamanOrganisasi::where('user_id', $user->id)->get(),
            'magang' => PengalamanMagang::where('user_id', $user->id)->get(),
            'keahlian' => KeahlianTambahan::where('user_id', $user->id)->get(),
            'lain' => LainLain::where('user_id', $user->id)->get(),
        ];

        $formatList = function ($data, $fieldNama, $fieldSertifikat) {
            if ($data->isEmpty()) return '-';
            $text = '';
            foreach ($data as $index => $item) {
                $no = $index + 1;
                $nama = $item->$fieldNama ?? '-';
                $sertifikat = $item->$fieldSertifikat ?? '-';
                $text .= "{$no}. {$nama} - ({$sertifikat})" . PHP_EOL;
            }
            return trim($text);
        };

        $processor->setValue('prestasi', (string) $formatList($tables['prestasi'], 'keterangan_indonesia', 'nomor_sertifikat'));
        $processor->setValue('organisasi', (string) $formatList($tables['organisasi'], 'organisasi', 'nomor_sertifikat'));
        $processor->setValue('magang', (string) $formatList($tables['magang'], 'keterangan_indonesia', 'nomor_sertifikat'));
        $processor->setValue('keahlian_tambahan', (string) $formatList($tables['keahlian'], 'nama_keahlian', 'nomor_sertifikat'));
        $processor->setValue('lain_lain', (string) $formatList($tables['lain'], 'nama_kegiatan', 'nomor_sertifikat'));

        // ==== Simpan file Word ====
        $outputDir = 'skpi/generated';
        Storage::makeDirectory($outputDir);

        $filename = 'SKPI_' . Str::slug($biodata->nama ?? 'mahasiswa', '_') . '_' . now()->format('Ymd_His') . '.docx';
        $savePath = storage_path('app/' . $outputDir . '/' . $filename);

        try {
            $processor->saveAs($savePath);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membuat dokumen: ' . $e->getMessage());
        }

        // ==== Simpan metadata ====
        SkpiCertificate::create([
            'user_id' => $user->id,
            'file_path' => $outputDir . '/' . $filename,
            'generated_at' => now(),
            'nomor_surat' => $nomorSurat,
        ]);

        // ==== Kirim file Word ke browser ====
        return response()->download($savePath)->deleteFileAfterSend(false);
    }
}


