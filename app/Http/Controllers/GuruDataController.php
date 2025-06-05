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
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('siswa', function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('industri', function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByRaw("CASE WHEN guru_id = ? THEN 1 ELSE 0 END DESC", [$gurus->id])
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Guru Dashboard', [
            'siswa' => $siswa,
            'industris' => $industris,
            'pkl' => $pkl,
            'gurus' => $gurus,
            'filters' => $request->only(['search'])
        ]);
    }
}
