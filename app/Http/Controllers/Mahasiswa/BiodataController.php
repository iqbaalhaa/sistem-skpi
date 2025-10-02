<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\BiodataMahasiswa;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $biodata = BiodataMahasiswa::where('user_id', $user->id)->first();
        $prodis = Prodi::all();
        return view('mahasiswa.biodata', compact('biodata', 'prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $biodata = \App\Models\BiodataMahasiswa::firstOrNew(['user_id' => $user->id]);
        $biodata->nim = $request->nim;
        $biodata->nama = $request->nama;
        $biodata->prodi_id = $request->prodi_id;
        $biodata->tempat_lahir = $request->tempat_lahir;
        $biodata->tanggal_lahir = $request->tanggal_lahir;
        $biodata->alamat = $request->alamat;
        $biodata->no_hp = $request->no_hp;
        $biodata->user_id = $user->id;

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'mhs_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/foto_mahasiswa', $filename);
            // Hapus foto lama jika ada
            if ($biodata->foto && Storage::exists('public/foto_mahasiswa/' . $biodata->foto)) {
                Storage::delete('public/foto_mahasiswa/' . $biodata->foto);
            }
            $biodata->foto = $filename;
        }

        $biodata->save();

        return redirect()->route('mahasiswa.biodata')->with('success', 'Biodata berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
