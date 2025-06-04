<?php

namespace App\Http\Controllers\Api;

use App\Models\Pkl;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PklController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $pkl = Pkl::with(['siswa', 'industri', 'guru'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pkl
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai'
        ]);

        $pkl = Pkl::create($validated);
        $pkl->load(['siswa', 'industri', 'guru']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data PKL berhasil ditambahkan',
            'data' => $pkl
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $pkl = Pkl::with(['siswa', 'industri', 'guru'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $pkl
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id): JsonResponse
    {
        $pkl = Pkl::findOrFail($id);

        // Debug incoming request
        \Log::info('Request type:', [
            'content_type' => $request->header('Content-Type'),
            'all_data' => $request->all()
        ]);

        // Handle both JSON and form-data
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validated = $request->validate([
            'siswa_id' => 'sometimes|required|exists:siswas,id',
            'industri_id' => 'sometimes|required|exists:industris,id',
            'guru_id' => 'sometimes|required|exists:gurus,id',
            'mulai' => 'sometimes|required|date',
            'selesai' => 'sometimes|required|date|after:mulai'
        ]);

        $pkl->update($validated);
        $pkl->refresh(); // Refresh model after update
        $pkl->load(['siswa', 'industri', 'guru']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data PKL berhasil diperbarui',
            'data' => $pkl
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $pkl = Pkl::findOrFail($id);
        $pkl->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data PKL berhasil dihapus'
        ]);
    }
}
