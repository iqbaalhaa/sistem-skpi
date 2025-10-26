<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|unique:users,username|min:3|max:255',
                'email' => 'required|string|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('Validation passed', $validated);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa', // Set default role to mahasiswa
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            Auth::login($user);
            Log::info('User logged in');

            // Set flash message untuk ditampilkan di dashboard
            session()->flash('registration_success', true);

            return redirect()->route('mahasiswa.dashboard');
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. ' . $e->getMessage()]);
        }
    }
}
