<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class siswaAuth extends Controller
{
    public function create()
    {
         return Inertia::render('Siswa Register');
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('images/siswa'), $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        $siswa = Siswa::create($data);
        Auth::guard('siswa')->login($siswa);

        return redirect()->intended('/siswa/dashboard'); // Mengubah redirect untuk memastikan path yang benar
    }

    public function login()
    {
        return Inertia::render('Siswa Login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('siswa')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('siswa.dashboard'); // Mengubah redirect ke nama route yang benar
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); // Mengubah redirect ke home setelah logout
    }
}
