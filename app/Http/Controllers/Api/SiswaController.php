<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $siswa = Siswa::latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $siswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswas,nis',
            'gender' => 'required|in:Laki-laki,Perempuan', // L = Laki-laki, P = Perempuan
            'alamat' => 'required|string',
            'kontak' => 'required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'required|email|unique:siswas,email',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:6',
            'status_pkl' => 'required|boolean'
        ]);

        // Jika ada gambar di-upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/gambar-siswa');
            $validated['gambar'] = basename($path); // atau simpan full path jika kamu perlukan
        }

        // Enkripsi password
        $validated['password'] = Hash::make($validated['password']);

        $siswa = Siswa::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $siswa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $siswa = Siswa::latest()->get()
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $siswa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id): JsonResponse
    {
        $siswa = Siswa::findOrFail($id);

        // Debug incoming request
        \Log::info('Request type:', [
            'content_type' => $request->header('Content-Type'),
            'all_data' => $request->all()
        ]);

        // Handle both JSON and form-data
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'nis' => 'sometimes|required|string|unique:siswas,nis',
            'gender' => 'sometimes|required|in:Laki-laki,Perempuan', // L = Laki-laki, P = Perempuan
            'alamat' => 'sometimes|required|string',
            'kontak' => 'sometimes|required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'sometimes|required|email|unique:siswas,email',
            'gambar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'sometimes|required|string|min:6',
            'status_pkl' => 'sometimes|required|boolean'
        ]);

        $siswa->update($validated);
        $siswa->refresh(); // Refresh model after update
        $siswa->load(['industri', 'guru']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data PKL berhasil diperbarui',
            'data' => $siswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}
