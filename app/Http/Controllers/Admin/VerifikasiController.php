<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenghargaanPrestasi;
use App\Models\PengalamanOrganisasi;
use App\Models\KompetensiBahasa;
use App\Models\PengalamanMagang;
use App\Models\KompetensiKeagamaan;
use App\Models\User;
use App\Models\PengajuanSkpi;
use Illuminate\Support\Facades\Log;

class VerifikasiController extends Controller
{
    public function verifikasi(Request $request, $id)
    {
        $type = $request->type;
        $status = $request->status;
        
        $model = match($type) {
            'prestasi' => PenghargaanPrestasi::class,
            'organisasi' => PengalamanOrganisasi::class,
            'bahasa' => KompetensiBahasa::class,
            'magang' => PengalamanMagang::class,
            'keagamaan' => KompetensiKeagamaan::class,
            default => null
        };

        if ($model) {
            $item = $model::findOrFail($id);
            
            $adminProdiId = auth()->user()->prodi_id;
            
            if (!$adminProdiId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum ditugaskan ke prodi manapun. Silahkan hubungi admin fakultas.'
                ]);
            }
            
            // Ambil prodi_id dengan prioritas dari biodata_mahasiswa.prodi_id, fallback ke users.prodi_id jika null
            $mahasiswaProdiId = optional($item->user->biodataMahasiswa)->prodi_id ?? $item->user->prodi_id;

            if ($mahasiswaProdiId != $adminProdiId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk memverifikasi data dari prodi lain'
                ]);
            }
            
            $item->verifikasi = $status;
            $item->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tipe data tidak valid'
        ]);
    }

    public function terimaMahasiswa(Request $request, $userId)
    {
        $adminProdiId = auth()->user()->prodi_id;
        if (!$adminProdiId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum ditugaskan ke prodi manapun. Silahkan hubungi admin fakultas.'
            ], 403);
        }

        $mahasiswa = User::with(['prestasi', 'organisasi', 'kompetensiBahasa', 'magang', 'kompetensiKeagamaan'])
            ->where('role', 'mahasiswa')
            ->findOrFail($userId);

        // Ambil prodi_id mahasiswa dengan prioritas dari biodata_mahasiswa
        $mahasiswaProdiId = optional($mahasiswa->biodataMahasiswa)->prodi_id ?? $mahasiswa->prodi_id;

        if ($mahasiswaProdiId != $adminProdiId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk memverifikasi data dari prodi lain'
            ], 403);
        }

        // Server-side check: semua item yang diajukan sudah diverifikasi (==1)
        $collections = [
            $mahasiswa->prestasi,
            $mahasiswa->organisasi,
            $mahasiswa->kompetensiBahasa,
            $mahasiswa->magang,
            $mahasiswa->kompetensiKeagamaan,
        ];

        $hasAnySubmission = false;
        $allApproved = true;

        foreach ($collections as $collection) {
            if ($collection && $collection->count() > 0) {
                $hasAnySubmission = true;
                // Semua item pada koleksi ini harus verifikasi === 1
                if ($collection->where('verifikasi', '!=', 1)->count() > 0) {
                    $allApproved = false;
                    break;
                }
            }
        }

        if (!$hasAnySubmission || !$allApproved) {
            return response()->json([
                'success' => false,
                'message' => 'Belum semua pengajuan mahasiswa disetujui.'
            ], 422);
        }

        // Tandai Pengajuan SKPI sebagai diterima_prodi
        $pengajuan = PengajuanSkpi::where('user_id', $mahasiswa->id)
            ->latest('created_at')
            ->first();

        if (!$pengajuan) {
            $pengajuan = new PengajuanSkpi();
            $pengajuan->user_id = $mahasiswa->id;
        }

        $pengajuan->status = 'diterima_prodi';
        $pengajuan->tanggal_verifikasi_prodi = now();
        $pengajuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan SKPI mahasiswa telah disetujui prodi.'
        ]);
    }
}