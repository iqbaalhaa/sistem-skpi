<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenghargaanPrestasi;
use App\Models\PengalamanOrganisasi;
use App\Models\KompetensiBahasa;
use App\Models\PengalamanMagang;
use App\Models\KompetensiKeagamaan;

class VerifikasiController extends Controller
{
    public function verifikasi(Request $request, $id)
    {
        $type = $request->input('type');
        $status = $request->input('status');
        
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
            $item->verifikasi = $status;
            $item->save();
            
            return redirect()->back()->with('success', 'Status berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Tipe data tidak valid');
    }
}