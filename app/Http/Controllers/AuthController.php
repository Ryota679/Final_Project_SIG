<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        // Cari user berdasarkan email atau username
        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'Username/email tidak ditemukan'
                ]);
        }

        // Verifikasi password
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors([
                    'password' => 'Password salah'
                ]);
        }

        // Login manual
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'TTL' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Gunakan bcrypt
                'TTL' => $request->TTL,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            // Debug info
            \Log::info('User registered successfully', [
                'id' => $user->id,
                'username' => $user->username,
                'password_hash' => $user->password
            ]);

            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Exception $e) {
            \Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat registrasi']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}