<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;


class guruAuth extends Controller
{
   public function login(){
    return Inertia::render('Guru Login');
   }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('guru')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            return redirect()->route('guru.dashboard'); // Mengubah redirect ke nama route yang benar
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

     public function destroy(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
