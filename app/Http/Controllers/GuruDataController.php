<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Industri;
use App\Models\Pkl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GuruDataController extends Controller
{
    public function dashboard(Request $request)
    {
        $gurus = Auth::guard('guru')->user();
        $siswa = Siswa::all();
        $industris = Industri::orderBy('nama')->get();

        // Get PKL data with relationships and pagination
       $pkl = Pkl::with(['industri', 'guru', 'siswa'])
    ->when($request->searchPkl, function ($query, $search) {
        $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$search}%"))
              ->orWhereHas('guru', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhereHas('industri', fn($q) => $q->where('nama', 'like', "%{$search}%"));
    })
    ->orderByRaw("CASE WHEN guru_id = ? THEN 1 ELSE 0 END DESC", [$gurus->id])
    ->paginate(5, ['*'], 'pkl_page');

    
        return Inertia::render('Guru Dashboard', [
            'siswa' => $siswa,
            'industris' => $industris,
            'pkl' => $pkl,
            'gurus' => $gurus
        ]);
    }
}
