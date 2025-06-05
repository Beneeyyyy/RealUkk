<?php

namespace App\Http\Controllers\Api;

use App\Models\Industri;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class IndustriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $siswa = Industri::latest()
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
            'bidang_usaha' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'required|email|unique:industris,email',
           
        ]);



        $industri = Industri::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data industri berhasil ditambahkan',
            'data' => $industri
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $industri = Industri::latest()->get()
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $industri
        ]);

     
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id): JsonResponse
    {
        $industri = Industri::findOrFail($id);

        // Debug incoming request
        \Log::info('Request type:', [
            'content_type' => $request->header('Content-Type'),
            'all_data' => $request->all()
        ]);

        // Handle both JSON and form-data
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'bidang_usaha' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string',
            'kontak' => 'sometimes|required|string|regex:/^[1-9][0-9]{7,14}$/',
            'email' => 'sometimes|required|email|unique:industris,email',
           
        ]);

        $industri->update($validated);
        $industri->refresh(); // Refresh model after update
        $industri->load(['industri', 'guru']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data industri berhasil diperbarui',
            'data' => $industri
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $industri = Industri::findOrFail($id);
        $industri->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
