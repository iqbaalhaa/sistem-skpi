<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\BiodataAdmin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class AdminBiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.biodata', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
    
        $request->validate([
            'nama' => 'required|string|max:150',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        // ambil atau buat biodata admin
        $admin = $user->biodataAdmin ?? new \App\Models\BiodataAdmin();
        $admin->user_id = $user->id;
        $admin->nama = $request->nama;
    
        if ($request->hasFile('foto')) {
            // hapus foto lama kalau ada
            if ($admin->foto && Storage::disk('public')->exists($admin->foto)) {
                Storage::disk('public')->delete($admin->foto);
            }
    
            // buat nama file unik
            $ext = $request->file('foto')->getClientOriginalExtension();
            $fileName = $user->id . '_' . time() . '.' . $ext;
    
            // simpan ke storage
            $path = $request->file('foto')->storeAs('admin-foto', $fileName, 'public');
    
            // simpan nama file ke database
            $admin->foto = $path;
        }
    
        // 🚀 inilah yang kurang
        $admin->save();
    
        return redirect()->route('admin.biodata')->with('success', 'Biodata berhasil diperbarui');
    }
    
     
}
