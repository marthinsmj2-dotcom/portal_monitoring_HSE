<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseFindingCategory;
use Illuminate\Http\Request;

class HseFindingCategoryController extends Controller
{
    public function index()
    {
        $data = HseFindingCategory::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kategori temuan berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = HseFindingCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori temuan berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HseFindingCategory $hseFindingCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail kategori temuan berhasil diambil.',
            'data' => $hseFindingCategory,
        ]);
    }

    public function update(
        Request $request,
        HseFindingCategory $hseFindingCategory
    ) {
        $validated = $request->validate([
            'nama_kategori' => 'sometimes|required|string|max:150',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $hseFindingCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori temuan berhasil diperbarui.',
            'data' => $hseFindingCategory->fresh(),
        ]);
    }

    public function destroy(HseFindingCategory $hseFindingCategory)
    {
        $hseFindingCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori temuan berhasil dihapus.',
        ]);
    }
}