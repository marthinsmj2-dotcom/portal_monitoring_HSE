<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseInspectionType;
use Illuminate\Http\Request;

class HseInspectionTypeController extends Controller
{
    public function index()
    {
        $data = HseInspectionType::with('checklists')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jenis inspeksi berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis_inspeksi' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = HseInspectionType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jenis inspeksi berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HseInspectionType $hseInspectionType)
    {
        $hseInspectionType->load('checklists');

        return response()->json([
            'success' => true,
            'message' => 'Detail jenis inspeksi berhasil diambil.',
            'data' => $hseInspectionType,
        ]);
    }

    public function update(
        Request $request,
        HseInspectionType $hseInspectionType
    ) {
        $validated = $request->validate([
            'nama_jenis_inspeksi' => 'sometimes|required|string|max:150',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $hseInspectionType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jenis inspeksi berhasil diperbarui.',
            'data' => $hseInspectionType->fresh(),
        ]);
    }

    public function destroy(HseInspectionType $hseInspectionType)
    {
        $hseInspectionType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis inspeksi berhasil dihapus.',
        ]);
    }
}