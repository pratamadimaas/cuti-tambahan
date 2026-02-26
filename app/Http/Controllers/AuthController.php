<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'sekre') {
                return redirect()->route('admin.buku-tamu.index');
            }
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

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            $name = Auth::user()->pegawai->nama ?? Auth::user()->username;

            if ($role === 'sekre') {
                return redirect()->route('admin.buku-tamu.index')->with('success', 'Selamat datang, ' . $name);
            }

            return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $name);
        }

        return back()->with('error', 'Username atau password salah')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}