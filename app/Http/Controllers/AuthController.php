<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Tentukan field yang akan digunakan untuk login
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Coba login dengan field yang ditentukan
        $credentials = [$fieldType => $login, 'password' => $password];
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect sesuai role
            $user = Auth::user();
            $role = strtolower($user->role);

            if ($role === 'mahasiswa') {
                return redirect()->intended(route('mahasiswa.dashboard'));
            } elseif ($role === 'admin_prodi') {
                return redirect()->intended(route('prodi.dashboard'));
            } elseif ($role === 'admin_fakultas') {
                return redirect()->intended(route('fakultas.dashboard'));
            }

            return redirect()->intended('/');
        }

        // Jika login gagal, coba dengan field lain
        if ($fieldType === 'email') {
            if (Auth::attempt(['username' => $login, 'password' => $password])) {
                $request->session()->regenerate();
                
                $user = Auth::user();
                $role = strtolower($user->role);

                if ($role === 'mahasiswa') {
                    return redirect()->intended(route('mahasiswa.dashboard'));
                } elseif ($role === 'admin_prodi') {
                    return redirect()->intended(route('prodi.dashboard'));
                } elseif ($role === 'admin_fakultas') {
                    return redirect()->intended(route('fakultas.dashboard'));
                }

                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'login' => 'Username/Email atau password salah.',
        ])->withInput($request->only('login'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
