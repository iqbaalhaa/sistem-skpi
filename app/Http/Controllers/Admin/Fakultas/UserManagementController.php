<?php

namespace App\Http\Controllers\Admin\Fakultas;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('prodi')
                    ->where('role', 'admin_prodi')
                    ->get();
        $prodis = Prodi::all();
        return view('admin.fakultas.manajemenuser', compact('users', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'prodi_id' => 'required|exists:prodi,id',
        ], [
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.min' => 'Username minimal 3 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'prodi_id.required' => 'Program studi harus dipilih',
            'prodi_id.exists' => 'Program studi tidak valid',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'prodi_id' => $request->prodi_id,
            'role' => 'admin_prodi'
        ]);

        return redirect()->route('admin.fakultas.manajemenuser.index')
            ->with('success', 'Admin prodi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)
                       ->where('role', 'admin_prodi')
                       ->first();
                       
            if (!$user) {
                return redirect()->route('admin.fakultas.manajemenuser.index')
                    ->with('error', 'Admin prodi tidak ditemukan');
            }

            $rules = [
                'username' => ['required', 'min:3', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'prodi_id' => 'required|exists:prodi,id',
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'min:6';
            }

            // Add edit_id for modal handling
            $request->merge(['edit_id' => $user->id]);

            $request->validate($rules, [
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan',
                'username.min' => 'Username minimal 3 karakter',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.min' => 'Password minimal 6 karakter',
                'prodi_id.required' => 'Program studi harus dipilih',
                'prodi_id.exists' => 'Program studi tidak valid',
            ]);

            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'prodi_id' => $request->prodi_id,
                'role' => 'admin_prodi'
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.fakultas.manajemenuser.index')
                ->with('success', 'Data admin prodi berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating admin prodi: ' . $e->getMessage());
            return redirect()->route('admin.fakultas.manajemenuser.index')
                ->with('error', 'Terjadi kesalahan saat mengupdate data admin prodi');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('id', $id)
                       ->where('role', 'admin_prodi')
                       ->first();

            if (!$user) {
                return redirect()->route('admin.fakultas.manajemenuser.index')
                    ->with('error', 'Admin prodi tidak ditemukan');
            }

            $user->delete();

            return redirect()->route('admin.fakultas.manajemenuser.index')
                ->with('success', 'Admin prodi berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting admin prodi: ' . $e->getMessage());
            return redirect()->route('admin.fakultas.manajemenuser.index')
                ->with('error', 'Terjadi kesalahan saat menghapus admin prodi');
        }
    }
}