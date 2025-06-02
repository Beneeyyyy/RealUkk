<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SiswaAuthController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswas',
            'email' => 'required|string|email|unique:siswas',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
        ]);

        $siswa = Siswa::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        Auth::login($siswa);

        return redirect()->route('dashboard');
    }

    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('siswa')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
