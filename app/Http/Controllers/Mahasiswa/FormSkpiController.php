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
use App\Models\KeahlianTambahan;
use App\Models\LainLain;
use App\Models\BiodataMahasiswa;

class FormSkpiController extends Controller
{
        public function index() {
            $mahasiswa = BiodataMahasiswa::with([
                'prestasi',
                'organisasi',
                'magang',
                'kompetensiBahasa',
                'kompetensiKeagamaan',
                'keahlianTambahan',
                'lainLain'
            ])
            ->where('user_id', Auth::id())
            ->get();

            return view('mahasiswa.formskpi', compact('mahasiswa'));
        }

        public function storePenghargaan(Request $request)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                // english description optional in the form
                'keterangan_inggris' => 'nullable|string|max:255',
                'jenis_organisasi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
                'nomor_sertifikat' => 'nullable|string|max:100',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'jenis_organisasi',
                'tahun',
                'catatan',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            // 0 = menunggu, 1 = diterima, 2 = ditolak (match KeahlianTambahan behavior)
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                // store in same generic 'bukti' folder like KeahlianTambahan
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            // ensure optional fields exist to avoid "no default value" DB errors
            // DB currently doesn't accept NULL for keterangan_inggris, set empty string if omitted
            $data['keterangan_inggris'] = $data['keterangan_inggris'] ?? '';
            $data['catatan'] = $data['catatan'] ?? null;
            $data['nomor_sertifikat'] = $data['nomor_sertifikat'] ?? null;

            PenghargaanPrestasi::create($data);

            return redirect()->back()->with('success', 'Data penghargaan berhasil ditambahkan.');
        }

        public function storeOrganisasi(Request $request)
        {
            $request->validate([
                'organisasi' => 'required|string|max:255',
                'tahun_awal' => 'required|integer|min:1900|max:2100',
                'tahun_akhir' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'organisasi',
                'tahun_awal',
                'tahun_akhir',
                'catatan',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $data['catatan'] = $data['catatan'] ?? null;
            $data['nomor_sertifikat'] = $data['nomor_sertifikat'] ?? null;

            PengalamanOrganisasi::create($data);

            return redirect()->back()->with('success', 'Data pengalaman organisasi berhasil ditambahkan.');
        }

        public function updateOrganisasi(Request $request, $id)
        {
            $request->validate([
                'organisasi' => 'required|string|max:255',
                'tahun_awal' => 'required|integer|min:1900|max:2100',
                'tahun_akhir' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
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
                'keterangan_inggris' => 'nullable|string|max:255',
                'lembaga' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'lembaga',
                'tahun',
                'catatan',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $data['keterangan_inggris'] = $data['keterangan_inggris'] ?? '';
            $data['catatan'] = $data['catatan'] ?? null;
            $data['nomor_sertifikat'] = $data['nomor_sertifikat'] ?? null;

            PengalamanMagang::create($data);

            return redirect()->back()->with('success', 'Data pengalaman magang berhasil ditambahkan.');
        }

        public function updateMagang(Request $request, $id)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'nullable|string|max:255',
                'lembaga' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $magang->update($data);

            return redirect()->back()->with('success', 'Data pengalaman magang berhasil diupdate.');
        }

        public function storeKeagamaan(Request $request)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                // english description optional
                'keterangan_inggris' => 'nullable|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
                'nomor_sertifikat' => 'nullable|string|max:100',
            ]);

            $data = $request->only([
                'keterangan_indonesia',
                'keterangan_inggris',
                'tahun',
                'catatan',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $data['keterangan_inggris'] = $data['keterangan_inggris'] ?? '';
            $data['catatan'] = $data['catatan'] ?? null;
            $data['nomor_sertifikat'] = $data['nomor_sertifikat'] ?? null;

            KompetensiKeagamaan::create($data);

            return redirect()->back()->with('success', 'Data kompetensi keagamaan berhasil ditambahkan.');
        }

        public function updateKeagamaan(Request $request, $id)
        {
            $request->validate([
                'keterangan_indonesia' => 'required|string|max:255',
                'keterangan_inggris' => 'required|string|max:255',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
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
                'keterangan_inggris' => 'nullable|string|max:255',
                'jenis_organisasi' => 'required|string|max:100',
                'tahun' => 'required|integer|min:1900|max:2100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $data['keterangan_inggris'] = $data['keterangan_inggris'] ?? null;
            $data['catatan'] = $data['catatan'] ?? null;
            $data['nomor_sertifikat'] = $data['nomor_sertifikat'] ?? null;

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

        // Keahlian Tambahan Methods
        public function storeKeahlianTambahan(Request $request)
        {
            $request->validate([
                'nama_keahlian' => 'required|string|max:150',
                'lembaga' => 'nullable|string|max:150',
                'tahun' => 'nullable|integer|min:1900|max:2100',
                'nomor_sertifikat' => 'nullable|string|max:100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = $request->only([
                'nama_keahlian',
                'lembaga',
                'tahun',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            KeahlianTambahan::create($data);

            return redirect()->back()->with('success', 'Data keahlian tambahan berhasil ditambahkan.');
        }

        public function updateKeahlianTambahan(Request $request, $id)
        {
            $request->validate([
                'nama_keahlian' => 'required|string|max:150',
                'lembaga' => 'nullable|string|max:150',
                'tahun' => 'nullable|integer|min:1900|max:2100',
                'nomor_sertifikat' => 'nullable|string|max:100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $keahlian = KeahlianTambahan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $data = $request->only([
                'nama_keahlian',
                'lembaga',
                'tahun',
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $keahlian->update($data);

            return redirect()->back()->with('success', 'Data keahlian tambahan berhasil diupdate.');
        }

        public function destroyKeahlianTambahan($id)
        {
            $keahlian = KeahlianTambahan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $keahlian->delete();
            return redirect()->back()->with('success', 'Data keahlian tambahan berhasil dihapus.');
        }

        // Lain Lain Methods
        public function storeLainLain(Request $request)
        {
            $request->validate([
                'nama_kegiatan' => 'required|string|max:150',
                'penyelenggara' => 'nullable|string|max:150',
                'tahun' => 'nullable|integer|min:1900|max:2100',
                'nomor_sertifikat' => 'nullable|string|max:100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = $request->only([
                'nama_kegiatan',
                'penyelenggara',
                'tahun',
                'nomor_sertifikat',
            ]);
            $data['user_id'] = Auth::id();
            $data['verifikasi'] = 0;

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            LainLain::create($data);

            return redirect()->back()->with('success', 'Data kegiatan lain berhasil ditambahkan.');
        }

        public function updateLainLain(Request $request, $id)
        {
            $request->validate([
                'nama_kegiatan' => 'required|string|max:150',
                'penyelenggara' => 'nullable|string|max:150',
                'tahun' => 'nullable|integer|min:1900|max:2100',
                'nomor_sertifikat' => 'nullable|string|max:100',
                'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $lainLain = LainLain::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $data = $request->only([
                'nama_kegiatan',
                'penyelenggara',
                'tahun',
                'nomor_sertifikat',
            ]);

            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }

            $lainLain->update($data);

            return redirect()->back()->with('success', 'Data kegiatan lain berhasil diupdate.');
        }

        public function destroyLainLain($id)
        {
            $lainLain = LainLain::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $lainLain->delete();
            return redirect()->back()->with('success', 'Data kegiatan lain berhasil dihapus.');
        }
}
