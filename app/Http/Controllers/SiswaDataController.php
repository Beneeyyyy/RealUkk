<?php

namespace App\Http\Controllers;


use App\Models\Guru;
use App\Models\Industri;
use App\Models\Pkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SiswaDataController extends Controller
{    public function dashboard(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $gurus = Guru::all();
        $allIndustris = Industri::orderBy('nama')->get();

        // Get PKL data with relationships and pagination
       $pkl = Pkl::with(['industri', 'guru', 'siswa'])
    ->when($request->searchPkl, function ($query, $search) {
        $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$search}%"))
              ->orWhereHas('guru', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhereHas('industri', fn($q) => $q->where('nama', 'like', "%{$search}%"));
    })
    ->orderByRaw("CASE WHEN siswa_id = ? THEN 1 ELSE 0 END DESC", [$siswa->id])
    ->paginate(5, ['*'], 'pkl_page');

        // Get Industri data with search filter
        $industris = Industri::query()
            ->when($request->searchIndustris, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('bidang_usaha', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5, ['*'], 'industri_page');  // Use separate page parameter

        return Inertia::render('Siswa Dashboard', [
            'siswa' => $siswa,
            'industris' => $industris,
            'allIndustris' => $allIndustris,
            'pkl' => $pkl,
            'gurus' => $gurus
        ]);
    }

    public function storeIndustri(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'bidang_usaha' => 'required|string',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'email' => 'required|email|unique:industris,email'
        ]);

        Industri::create($request->all());
        return redirect()->back();
    }

    public function updateIndustri(Request $request, Industri $industri)
    {
        $request->validate([
            'nama' => 'required|string',
            'bidang_usaha' => 'required|string',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'email' => 'required|email|unique:industris,email,' . $industri->id
        ]);

        $industri->update($request->all());
        return redirect()->back();
    }

    public function deleteIndustri(Industri $industri)
    {
        $industri->delete();
        return redirect()->back();
    }    public function storePkl(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        
        $request->validate([
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai'
        ]);

        Pkl::create([
            'siswa_id' => $siswa->id,
            'industri_id' => $request->industri_id,
            'guru_id' => $request->guru_id,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai
        ]);

        // Update siswa status_pkl to true
        $siswa->update(['status_pkl' => true]);

        return redirect()->back();
    }

    public function updatePkl(Request $request, Pkl $pkl)
    {
        $request->validate([
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai',
        ]);

        // Pastikan siswa hanya bisa mengedit PKL miliknya
        if ($pkl->siswa_id !== auth()->id()) {
            abort(403);
        }

        $pkl->update($request->all());

        return redirect()->back();
    }    public function deletePkl(Pkl $pkl)
    {
        $siswa = Auth::guard('siswa')->user();
        
        if ($pkl->siswa_id !== $siswa->id) {
            abort(403);
        }

        $pkl->delete();
        
        // Update siswa status_pkl to false
        $siswa->update(['status_pkl' => false]);
        
        return redirect()->back();
    }
    public function getIndustris(Request $request)
    {
        $industris = Industri::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('bidang_usaha', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($industris);
        }

        return $industris;
    }
}
