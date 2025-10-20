<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\BiodataMahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BiodataController extends Controller
{
    /**
     * Menampilkan halaman biodata mahasiswa
     */
    public function index()
    {
        $user = auth()->user();
        $biodata = BiodataMahasiswa::where('user_id', $user->id)->first();
        $prodis = Prodi::all();

        return view('mahasiswa.biodata', compact('biodata', 'prodis'));
    }

    /**
     * Update atau simpan data biodata mahasiswa
     */
    public function update(Request $request)
    {
    $user = auth()->user();
        Log::info('BiodataController@update: Mulai update biodata untuk user_id=' . $user->id);

        // Validasi input
        try {
            $validated = $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'prodi_id' => 'required|integer|exists:prodi,id',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tahun_masuk' => 'nullable|integer|min:2000|max:2099',
            'judul_skripsi' => 'nullable|string|max:255',
            'lama_studi' => 'nullable|string|max:50',
            'tanggal_lulus' => 'nullable|date',
            'nomor_ijazah' => 'nullable|string|max:100',
            'ipk' => 'nullable|numeric|between:0,4.00',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('BiodataController@update: Validasi gagal', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Ambil atau buat data baru
    $biodata = BiodataMahasiswa::firstOrNew(['user_id' => $user->id]);
        Log::info('BiodataController@update: Data sebelum update', $biodata->toArray());

        $biodata->fill([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'prodi_id' => $validated['prodi_id'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'tahun_masuk' => $validated['tahun_masuk'] ?? null,
            'judul_skripsi' => $validated['judul_skripsi'] ?? null,
            'lama_studi' => $validated['lama_studi'] ?? null,
            'tanggal_lulus' => $validated['tanggal_lulus'] ?? null,
            'nomor_ijazah' => $validated['nomor_ijazah'] ?? null,
            'ipk' => $validated['ipk'] ?? null,
            'user_id' => $user->id,
        ]);

        //  Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'mhs_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('public/foto_mahasiswa', $filename);

            // Hapus foto lama
            if ($biodata->foto && Storage::exists('public/foto_mahasiswa/' . $biodata->foto)) {
                Storage::delete('public/foto_mahasiswa/' . $biodata->foto);
            }

            $biodata->foto = $filename;
        }

    $biodata->save();
        Log::info('BiodataController@update: Data setelah update', $biodata->toArray());

    return redirect()->route('mahasiswa.biodata')->with('success', 'Biodata berhasil diperbarui!');
    }
}
