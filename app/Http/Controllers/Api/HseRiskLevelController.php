<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseRiskLevel;
use Illuminate\Http\Request;

class HseRiskLevelController extends Controller
{
    public function index()
    {
        $data = HseRiskLevel::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data tingkat risiko berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'skor_min' => 'required|integer|min:0',
            'skor_max' => 'required|integer|min:0|gte:skor_min',
            'deskripsi' => 'nullable|string',
        ]);

        $data = HseRiskLevel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tingkat risiko berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HseRiskLevel $hseRiskLevel)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail tingkat risiko berhasil diambil.',
            'data' => $hseRiskLevel,
        ]);
    }

    public function update(
        Request $request,
        HseRiskLevel $hseRiskLevel
    ) {
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'skor_min' => 'sometimes|required|integer|min:0',
            'skor_max' => 'sometimes|required|integer|min:0|gte:skor_min',
            'deskripsi' => 'nullable|string',
        ]);

        $hseRiskLevel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tingkat risiko berhasil diperbarui.',
            'data' => $hseRiskLevel->fresh(),
        ]);
    }

    public function destroy(HseRiskLevel $hseRiskLevel)
    {
        $hseRiskLevel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tingkat risiko berhasil dihapus.',
        ]);
    }
}