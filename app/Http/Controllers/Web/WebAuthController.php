<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Pengguna;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session('user')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $credentials = $request->only('username', 'password');
            
            $user = Pengguna::where('username', $credentials['username'])->first();
            $loginSuccess = false;

            // 1. Coba login manual (bypass Auth::attempt issues)
            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::guard('web')->login($user);
                $loginSuccess = true;
            } 
            // 2. Fallback ke standard Auth::attempt
            elseif (Auth::guard('web')->attempt($credentials)) {
                $loginSuccess = true;
            }

            if ($loginSuccess) {
                $pengguna = Auth::guard('web')->user();

                // Cek status user
                if ($pengguna->status === 'inactive') {
                    Auth::guard('web')->logout();
                    return back()->withErrors(['login' => 'Akun anda tidak aktif'])->withInput();
                }

                // Regenerate session untuk security
                $request->session()->regenerate();

                // Store user data in session
                session([
                    'user' => [
                        'id' => $pengguna->id,
                        'username' => $pengguna->username,
                        'nama_lengkap' => $pengguna->nama_lengkap,
                        'role' => $pengguna->role,
                    ],
                ]);

                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            return back()->withErrors(['login' => 'Username atau password salah'])->withInput();
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors(['login' => 'Terjadi kesalahan saat login: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Logout dari auth
        try {
            Auth::guard('web')->logout();
        } catch (\Exception $e) {
            // Ignore error
        }

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    public function showProfile()
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect()->route('login');
        }

        $user = Pengguna::findOrFail($sessionUser['id']);
        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect()->route('login');
        }

        $user = Pengguna::findOrFail($sessionUser['id']);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|unique:pengguna,username,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'role' => $user->role,
            ],
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
