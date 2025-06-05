<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $siswa = Guru::latest()
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
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip',
            'gender' => 'required|in:Laki-laki,Perempuan', // L = Laki-laki, P = Perempuan
            'alamat' => 'required|string',
            'kontak' => 'required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'required|email|unique:gurus,email',
            'password' => 'required|string|min:6',
        ]);

       

        // Enkripsi password
        $validated['password'] = Hash::make($validated['password']);

        $siswa = Guru::create($validated);

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
        $siswa = Guru::latest()->get()
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
        $siswa = Guru::findOrFail($id);

        // Debug incoming request
        \Log::info('Request type:', [
            'content_type' => $request->header('Content-Type'),
            'all_data' => $request->all()
        ]);

        // Handle both JSON and form-data
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'nip' => 'sometimes|required|string|unique:gurus,nip',
            'gender' => 'sometimes|required|in:Laki-laki,Perempuan', // L = Laki-laki, P = Perempuan
            'alamat' => 'sometimes|required|string',
            'kontak' => 'sometimes|required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'sometimes|required|email|unique:gurus,email',
            'password' => 'sometimes|required|string|min:6',
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
        $siswa = Guru::findOrFail($id);
        $siswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
