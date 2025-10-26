<?php

namespace App\Http\Controllers\Admin\Fakultas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSkpi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\PengajuanSkpi::with(['user.biodataMahasiswa.prodi', 'certificate'])
            ->where('status', 'diterima_fakultas');
            
        // Filter by prodi if specified
        if ($request->has('prodi') && $request->prodi) {
            $query->whereHas('user.biodataMahasiswa', function($q) use ($request) {
                $q->where('prodi_id', $request->prodi);
            });
        }
        
        $laporan = $query->orderByDesc('updated_at')->get();

        // Ambil daftar prodi untuk dropdown filter
        $prodiList = DB::table('prodi')
            ->select('id', 'nama_prodi')
            ->get();

        return view('admin.fakultas.laporan.index', compact('laporan', 'prodiList'));
    }

    public function export(Request $request)
    {
        $laporan = PengajuanSkpi::join('biodata_mahasiswa', 'pengajuan_skpi.user_id', '=', 'biodata_mahasiswa.user_id')
            ->join('prodi', 'biodata_mahasiswa.prodi_id', '=', 'prodi.id')
            ->leftJoin('skpi_certificates', 'skpi_certificates.user_id', '=', 'pengajuan_skpi.user_id')
            ->where('pengajuan_skpi.status', 'diterima_fakultas')
            ->when($request->prodi, function ($query) use ($request) {
                $query->where('prodi.id', $request->prodi);
            })
            ->select(
                'biodata_mahasiswa.nama',
                'biodata_mahasiswa.nim',
                'prodi.nama_prodi',
                'skpi_certificates.nomor_surat',
                'pengajuan_skpi.updated_at'
            )
            ->orderByDesc('pengajuan_skpi.updated_at')
            ->get();

        $filename = 'Laporan_SKPI_' . now()->format('Ymd_His') . '.csv';
        $csvData = "Nama,NIM,Prodi,Nomor Surat,Tanggal Disetujui\n";

        foreach ($laporan as $item) {
            $nomorSurat = $item->nomor_surat ?? 'Belum Digenerate';
            $csvData .= "{$item->nama},{$item->nim},{$item->nama_prodi},{$nomorSurat}," . $item->updated_at->format('d-m-Y') . "\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
