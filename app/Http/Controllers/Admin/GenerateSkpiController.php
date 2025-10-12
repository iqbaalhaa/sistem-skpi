<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use App\Models\BiodataMahasiswa;
use App\Models\User;
use App\Models\SkpiCertificate;

class GenerateSkpiController extends Controller
{
    public function index()
    {
        $adminProdiId = Auth::user()->prodi_id;

        $mahasiswa = BiodataMahasiswa::with(['user.pengajuanSkpi' => function($query) {
                $query->latest('created_at');
            }])
            ->whereHas('user', function ($q) use ($adminProdiId) {
                $q->where('role', 'mahasiswa')
                  ->where('prodi_id', $adminProdiId);
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

        if ($user->prodi_id !== $adminProdiId) {
            abort(403, 'Anda tidak memiliki akses untuk mahasiswa prodi lain.');
        }

        // Path template .docx: coba beberapa lokasi umum
        $candidatePaths = [
            storage_path('templates/skpi_template.docx'),                 // storage/templates
            storage_path('app/templates/skpi_template.docx'),             // storage/app/templates
            public_path('templates/skpi_template.docx'),                  // public/templates
            base_path('templates/skpi_template.docx'),                    // project-root/templates
            public_path('storage/templates/skpi_template.docx'),          // public/storage/templates
            public_path('storage/app/templates/skpi_template.docx'),      // public/storage/app/templates
        ];

        // Normalisasi path untuk Windows/Linux
        $normalizedCandidates = array_map(function ($p) {
            return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $p);
        }, $candidatePaths);

        $templatePath = null;
        foreach ($normalizedCandidates as $path) {
            if (file_exists($path)) {
                $templatePath = $path;
                break;
            }
        }
        if (!$templatePath) {
            $checked = implode(' | ', $normalizedCandidates);
            return back()->with('error', 'Template SKPI tidak ditemukan. Coba letakkan di salah satu: ' . $checked);
        }

        $biodata = $user->biodataMahasiswa;

        $processor = new TemplateProcessor($templatePath);

        // Set placeholder dasar (samakan dengan placeholder di file Word)
        $processor->setValue('nama_lengkap', $biodata->nama ?? '-');
        $processor->setValue('nim', $biodata->nim ?? '-');
        $processor->setValue('prodi', optional($user->prodi)->nama_prodi ?? '-');
        $processor->setValue('akreditasi', optional($user->prodi)->akreditasi ?? '-');
        $processor->setValue('jenjang_pendidikan', optional($user->prodi)->jenjang_pendidikan ?? '-');
        $processor->setValue('gelar', optional($user->prodi)->gelar ?? '-');
        $processor->setValue('ttl', $biodata->tempat_lahir . ', ' . \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y'));
        $processor->setValue('tahun_masuk', $biodata->tahun_masuk ?? '-');
        $processor->setValue('tanggal_cetak', Carbon::now()->translatedFormat('d F Y'));
        

        // Anda bisa menambahkan setValue lainnya sesuai placeholder template Word

        // Simpan hasil
        $dir = 'skpi/generated';
        $filename = 'SKPI_' . preg_replace('/\s+/', '_', ($biodata->nama ?? 'mahasiswa')) . '_' . now()->format('Ymd_His') . '.docx';
        Storage::makeDirectory($dir);
        $savePath = storage_path('app/' . $dir . '/' . $filename);
        try {
            $processor->saveAs($savePath);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membuat dokumen: ' . $e->getMessage());
        }

        // Simpan metadata ke DB
        SkpiCertificate::create([
            'user_id' => $user->id,
            'file_path' => $dir . '/' . $filename,
            'generated_at' => now(),
        ]);

        return response()->download($savePath)->deleteFileAfterSend(false);
    }
}


