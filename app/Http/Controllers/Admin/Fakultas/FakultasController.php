<?php

namespace App\Http\Controllers\Admin\Fakultas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Models\Prodi;
use App\Models\PengajuanSkpi;

class FakultasController extends Controller
{
    public function index()
    {
        $prodi = Prodi::all()->map(function ($item) {
            // hitung jumlah SKPI diterima_prodi berdasarkan relasi prodi mahasiswa
            $item->pending_ttd = PengajuanSkpi::where('status', 'diterima_prodi')
                ->whereHas('user.biodataMahasiswa', function ($q) use ($item) {
                    $q->where('prodi_id', $item->id);
                })
                ->count();
            return $item;
        });

        return view('admin.fakultas.verifikasiskpi', compact('prodi'));
    }

    /**
     * Mengembalikan JSON list pengajuan SKPI yang statusnya 'diterima_prodi' untuk prodi tertentu.
     */
    public function getMahasiswaByProdi($id): JsonResponse
    {
        $pengajuan = PengajuanSkpi::whereHas('user.biodataMahasiswa', function($q) use ($id) {
            $q->where('prodi_id', $id);
        })
        ->where('status', 'diterima_prodi')
        ->with(['user.biodataMahasiswa']) // pastikan relasi ada
        ->orderBy('tanggal_verifikasi_prodi', 'desc')
        ->get();

        // Kembalikan JSON (Laravel akan otomatis serialise relasi)
        return response()->json($pengajuan);
    }

/**
 * Tandatangani SKPI (AJAX)
 */
    public function tandaTangan(Request $request, $id): JsonResponse
    {
        try {
            $skpi = PengajuanSkpi::findOrFail($id);

            if ($skpi->status !== 'diterima_prodi') {
                return response()->json(['success' => false, 'message' => 'Hanya SKPI yang diterima prodi yang bisa ditandatangani.'], 422);
            }

            // Generate nomor surat
            $tahun = date('Y');
            $bulan = date('m');
            $lastNumber = \App\Models\SkpiCertificate::max('id') ?? 0;
            $newNumber = $lastNumber + 1;
            $nomorSurat = sprintf("B. %03d/VIII.I/PP.01.1/%s/%s", $newNumber, $bulan, $tahun);

            // Simpan nomor surat ke dalam database
            $certificate = \App\Models\SkpiCertificate::updateOrCreate(
                ['user_id' => $skpi->user_id],
                [
                    'nomor_surat' => $nomorSurat,
                    'generated_at' => now(),
                    'file_path' => 'pending' // Memberikan nilai default string untuk file_path karena tidak boleh null
                ]
            );

            // Update status pengajuan SKPI
            $skpi->status = 'diterima_fakultas';
            $skpi->tanggal_verifikasi_fakultas = now();
            $skpi->save();

            return response()->json(['success' => true, 'message' => 'SKPI berhasil ditandatangani fakultas dan nomor surat telah digenerate.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in tandaTangan method: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

}

