<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BiodataMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function create()
    {
        return view('prodi.mahasiswa.create');
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

    public function edit($id)
    {
        $mahasiswa = BiodataMahasiswa::where('biodata_mahasiswa.id', $id)
            ->join('users', 'biodata_mahasiswa.user_id', '=', 'users.id')
            ->select('biodata_mahasiswa.*', 'users.username', 'users.email')
            ->firstOrFail();

        return view('prodi.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa
        ]);
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

        $biodata->delete();
        $user->delete();

        return redirect()->route('prodi.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus');
    }
}