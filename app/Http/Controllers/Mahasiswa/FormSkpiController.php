<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PenghargaanPrestasi;
use App\Models\PengalamanOrganisasi;
use App\Models\KompetensiBahasa;
use App\Models\PengalamanMagang;
use App\Models\KompetensiKeagamaan;
use App\Models\BiodataMahasiswa;

class FormSkpiController extends Controller
{
        public function index() {
            $mahasiswa = BiodataMahasiswa::with([
                'prestasi',
                'organisasi',
                'magang',
                'kompetensiBahasa',
                'kompetensiKeagamaan'
            ])
            ->where('user_id', Auth::id())
            ->get();

            return view('mahasiswa.formskpi', compact('mahasiswa'));
        }

        public function storePenghargaan(Request $request)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'jenis_organisasi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'jenis_organisasi',
                'tahun',
                'catatan',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = false;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_penghargaan', 'public');
            }

            PenghargaanPrestasi::create($data);

            return redirect()->back()->with('success', 'Data penghargaan berhasil ditambahkan.');
        }

        public function storeOrganisasi(Request $request)
        {
            $request->validate([
                'organisasi' => 'required|string|max:255',
                'tahun_awal' => 'required|integer|min:1900|max:2100',
                'tahun_akhir' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'organisasi',
                'tahun_awal',
                'tahun_akhir',
                'catatan',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = false;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_organisasi', 'public');
            }

            PengalamanOrganisasi::create($data);

            return redirect()->back()->with('success', 'Data pengalaman organisasi berhasil ditambahkan.');
        }

        public function updateOrganisasi(Request $request, $id)
        {
            $request->validate([
                'organisasi' => 'required|string|max:255',
                'tahun_awal' => 'required|integer|min:1900|max:2100',
                'tahun_akhir' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $organisasi = PengalamanOrganisasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $data = $request->only([
                'organisasi',
                'tahun_awal',
                'tahun_akhir',
                'catatan',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_organisasi', 'public');
            }

            $organisasi->update($data);

            return redirect()->back()->with('success', 'Data pengalaman organisasi berhasil diupdate.');
        }

        public function storeKompetensiBahasa(Request $request)
        {
            $request->validate([
                'nama_kompetensi' => 'required|string|max:255',
                'skor_kompetensi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'nama_kompetensi',
                'skor_kompetensi',
                'tahun',
                'catatan',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = false;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_bahasa', 'public');
            }

            KompetensiBahasa::create($data);

            return redirect()->back()->with('success', 'Data kompetensi bahasa berhasil ditambahkan.');
        }

        public function updateKompetensiBahasa(Request $request, $id)
        {
            $request->validate([
                'nama_kompetensi' => 'required|string|max:255',
                'skor_kompetensi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $bahasa = KompetensiBahasa::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $data = $request->only([
                'nama_kompetensi',
                'skor_kompetensi',
                'tahun',
                'catatan',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_bahasa', 'public');
            }

            $bahasa->update($data);

            return redirect()->back()->with('success', 'Data kompetensi bahasa berhasil diupdate.');
        }

        public function storeMagang(Request $request)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'lembaga' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'lembaga',
                'tahun',
                'catatan',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = false;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_magang', 'public');
            }

            PengalamanMagang::create($data);

            return redirect()->back()->with('success', 'Data pengalaman magang berhasil ditambahkan.');
        }

        public function updateMagang(Request $request, $id)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'lembaga' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $magang = PengalamanMagang::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'lembaga',
                'tahun',
                'catatan',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_magang', 'public');
            }

            $magang->update($data);

            return redirect()->back()->with('success', 'Data pengalaman magang berhasil diupdate.');
        }

        public function storeKeagamaan(Request $request)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'tahun',
                'catatan',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = false;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_keagamaan', 'public');
            }

            KompetensiKeagamaan::create($data);

            return redirect()->back()->with('success', 'Data kompetensi keagamaan berhasil ditambahkan.');
        }

        public function updateKeagamaan(Request $request, $id)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $keagamaan = KompetensiKeagamaan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'tahun',
                'catatan',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_keagamaan', 'public');
            }

            $keagamaan->update($data);

            return redirect()->back()->with('success', 'Data kompetensi keagamaan berhasil diupdate.');
        }

        public function editPenghargaan($id)
        {
            $penghargaan = PenghargaanPrestasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            return response()->json($penghargaan);
        }

        public function updatePenghargaan(Request $request, $id)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'jenis_organisasi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file',
                'catatan' => 'nullable|string',
            ]);

            $penghargaan = PenghargaanPrestasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'jenis_organisasi',
                'tahun',
                'catatan',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti_penghargaan', 'public');
            }

            $penghargaan->update($data);

            return redirect()->back()->with('success', 'Data penghargaan berhasil diupdate.');
        }

        public function destroyPenghargaan($id)
        {
            $penghargaan = PenghargaanPrestasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $penghargaan->delete();
            return redirect()->back()->with('success', 'Data penghargaan berhasil dihapus.');
        }
}
