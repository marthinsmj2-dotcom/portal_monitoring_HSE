<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeCategory;
use Illuminate\Http\Request;

class HsePpeCategoryController extends Controller
{
    public function index()
    {
        $data = HsePpeCategory::with('items')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kategori APD berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:150',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'status' => [
                'boolean',
            ],
        ]);

        $data = HsePpeCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori APD berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HsePpeCategory $hsePpeCategory)
    {
        $hsePpeCategory->load('items');

        return response()->json([
            'success' => true,
            'message' => 'Detail kategori APD berhasil diambil.',
            'data' => $hsePpeCategory,
        ]);
    }

    public function update(
        Request $request,
        HsePpeCategory $hsePpeCategory
    ) {
        $validated = $request->validate([
            'nama_kategori' => [
                'sometimes',
                'required',
                'string',
                'max:150',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'status' => [
                'boolean',
            ],
        ]);

        $hsePpeCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori APD berhasil diperbarui.',
            'data' => $hsePpeCategory->fresh(),
        ]);
    }

    public function destroy(HsePpeCategory $hsePpeCategory)
    {
        $hsePpeCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori APD berhasil dihapus.',
        ]);
    }
}