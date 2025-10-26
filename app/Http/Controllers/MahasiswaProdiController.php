<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BiodataMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaProdiController extends Controller
{
    public function index()
    {
        // Get current admin prodi's prodi_id
        $prodiId = Auth::user()->prodi_id;
        
        // Get all students from this prodi
        $mahasiswa = BiodataMahasiswa::where('biodata_mahasiswa.prodi_id', $prodiId)
            ->join('users', 'biodata_mahasiswa.user_id', '=', 'users.id')
            ->select('biodata_mahasiswa.*', 'users.username', 'users.email')
            ->get();

        return view('admin.prodi.kelolamahasiswa', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:biodata_mahasiswa',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        // Create user account
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'mahasiswa';
        $user->prodi_id = Auth::user()->prodi_id;
        $user->save();

        // Create biodata
        $biodata = new BiodataMahasiswa();
        $biodata->user_id = $user->id;
        $biodata->nama = $request->nama;
        $biodata->nim = $request->nim;
        $biodata->prodi_id = Auth::user()->prodi_id;
        $biodata->save();

        return redirect()->route('prodi.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $biodata = BiodataMahasiswa::findOrFail($id);
        
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:biodata_mahasiswa,nim,' . $id,
            'email' => 'required|email|unique:users,email,' . $biodata->user_id,
            'username' => 'required|unique:users,username,' . $biodata->user_id,
        ]);

        // Update user account
        $user = User::find($biodata->user_id);
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        // Update biodata
        $biodata->nama = $request->nama;
        $biodata->nim = $request->nim;
        $biodata->save();

        return redirect()->route('prodi.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $biodata = BiodataMahasiswa::findOrFail($id);
        $user = User::find($biodata->user_id);
        $userId = $biodata->user_id;

        // Hapus file foto profil jika ada
        if ($biodata->foto && Storage::disk('public')->exists($biodata->foto)) {
            Storage::disk('public')->delete($biodata->foto);
        }

        // Hapus data penghargaan prestasi dan file bukti
        $penghargaan = \App\Models\PenghargaanPrestasi::where('user_id', $userId)->get();
        foreach ($penghargaan as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data pengalaman organisasi dan file bukti
        $organisasi = \App\Models\PengalamanOrganisasi::where('user_id', $userId)->get();
        foreach ($organisasi as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data kompetensi bahasa dan file bukti
        $bahasa = \App\Models\KompetensiBahasa::where('user_id', $userId)->get();
        foreach ($bahasa as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data pengalaman magang dan file bukti
        $magang = \App\Models\PengalamanMagang::where('user_id', $userId)->get();
        foreach ($magang as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data kompetensi keagamaan dan file bukti
        $keagamaan = \App\Models\KompetensiKeagamaan::where('user_id', $userId)->get();
        foreach ($keagamaan as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data keahlian tambahan dan file bukti
        $keahlian = \App\Models\KeahlianTambahan::where('user_id', $userId)->get();
        foreach ($keahlian as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data lain-lain dan file bukti
        $lainlain = \App\Models\LainLain::where('user_id', $userId)->get();
        foreach ($lainlain as $item) {
            if ($item->bukti && Storage::disk('public')->exists($item->bukti)) {
                Storage::disk('public')->delete($item->bukti);
            }
            $item->delete();
        }

        // Hapus data pengajuan SKPI
        \App\Models\PengajuanSkpi::where('user_id', $userId)->delete();

        // Hapus sertifikat SKPI dan file
        $sertifikat = \App\Models\SkpiCertificate::where('user_id', $userId)->first();
        if ($sertifikat) {
            if ($sertifikat->file_path && Storage::disk('public')->exists($sertifikat->file_path)) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }
            $sertifikat->delete();
        }

        // Hapus biodata dan user
        $biodata->delete();
        $user->delete();

        return redirect()->route('prodi.mahasiswa.index')->with('success', 'Data mahasiswa dan semua data terkait berhasil dihapus');
    }
}