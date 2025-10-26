<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PengajuanSkpi;
use App\Models\User;
use App\Models\Prodi;
use App\Models\SkpiCertificate;
use App\Models\PenghargaanPrestasi;
use App\Models\PengalamanOrganisasi;
use App\Models\PengalamanMagang;
use App\Models\KeahlianTambahan;
use App\Models\LainLain;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SkpiController extends Controller
{
    public function cetak($id)
    {
        // Verify the SKPI belongs to the authenticated user
        $pengajuan = PengajuanSkpi::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'diterima_fakultas')
            ->firstOrFail();

        // Get user data
        $user = User::with('biodataMahasiswa')->findOrFail(Auth::id());
        $biodata = $user->biodataMahasiswa;

        if (!$biodata) {
            return back()->with('error', 'Biodata tidak ditemukan.');
        }

        // === Template path ===
        $templatePath = public_path('templates/skpi_template.docx');
        if (!file_exists($templatePath)) {
            return back()->with('error', "Template SKPI tidak ditemukan di {$templatePath}");
        }

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
        // Ambil nomor surat dari database yang sudah digenerate oleh admin fakultas
        $certificate = SkpiCertificate::where('user_id', Auth::id())->first();
        $nomorSurat = $certificate ? $certificate->nomor_surat : '(Belum Digenerate)';
        $processor->setValue('nomor_surat', $nomorSurat);

        // Get Prodi data
        $prodi = null;
        if (!empty($biodata->prodi_id)) {
            $prodi = Prodi::find($biodata->prodi_id);
        }

        // ==== ISI DATA DASAR ====
        $processor->setValue('nama_lengkap', $biodata->nama ?? '-');
        $processor->setValue('nim', $biodata->nim ?? '-');
        $processor->setValue('prodi', $biodata->nama_prodi ?? optional($prodi)->nama_prodi ?? '-');
        $processor->setValue('akreditasi', optional($prodi)->akreditasi ?? '-');
        $processor->setValue('jenjang_pendidikan', optional($prodi)->jenjang_pendidikan ?? '-');
        $processor->setValue('gelar', optional($prodi)->gelar ?? '-');
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
            'prestasi' => PenghargaanPrestasi::where('user_id', Auth::id())->get(),
            'organisasi' => PengalamanOrganisasi::where('user_id', Auth::id())->get(),
            'magang' => PengalamanMagang::where('user_id', Auth::id())->get(),
            'keahlian' => KeahlianTambahan::where('user_id', Auth::id())->get(),
            'lain' => LainLain::where('user_id', Auth::id())->get(),
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

        $processor->setValue('prestasi', $formatList($tables['prestasi'], 'keterangan_indonesia', 'nomor_sertifikat'));
        $processor->setValue('organisasi', $formatList($tables['organisasi'], 'organisasi', 'nomor_sertifikat'));
        $processor->setValue('magang', $formatList($tables['magang'], 'keterangan_indonesia', 'nomor_sertifikat'));
        $processor->setValue('keahlian_tambahan', $formatList($tables['keahlian'], 'nama_keahlian', 'nomor_sertifikat'));
        $processor->setValue('lain_lain', $formatList($tables['lain'], 'nama_kegiatan', 'nomor_sertifikat'));

        // ==== Simpan file Word ====
        $outputDir = 'public/storage/skpi';
        Storage::makeDirectory($outputDir);

        $filename = 'SKPI_' . Str::slug($biodata->nama ?? 'mahasiswa', '_') . '_' . now()->format('Ymd_His') . '.docx';
        $savePath = storage_path('app/' . $outputDir . '/' . $filename);

        try {
            $processor->saveAs($savePath);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membuat dokumen: ' . $e->getMessage());
        }

        // Buat symlink jika belum ada
        if (!file_exists(public_path('storage'))) {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        }

        // ==== Simpan metadata ====
        SkpiCertificate::create([
            'user_id' => Auth::id(),
            'file_path' => 'storage/skpi/' . $filename,
            'generated_at' => now(),
            'nomor_surat' => $nomorSurat,
        ]);

        // ==== Kirim file Word ke browser ====
        return response()->download($savePath)->deleteFileAfterSend(false);
    }
}