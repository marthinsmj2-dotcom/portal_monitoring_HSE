<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeItem;
use Illuminate\Http\Request;

class HsePpeItemController extends Controller
{
    public function index(Request $request)
    {
        $query = HsePpeItem::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data APD berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:hse_ppe_categories,id',
            ],
            'kode_apd' => [
                'required',
                'string',
                'max:50',
                'unique:hse_ppe_items,kode_apd',
            ],
            'nama_apd' => [
                'required',
                'string',
                'max:150',
            ],
            'ukuran' => [
                'nullable',
                'string',
                'max:50',
            ],
            'satuan' => [
                'required',
                'string',
                'max:50',
            ],
            'lokasi_penyimpanan' => [
                'nullable',
                'string',
                'max:150',
            ],
            'stok_minimum' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'status' => [
                'boolean',
            ],
        ]);

        $data = HsePpeItem::create($validated);

        $data->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Data APD berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HsePpeItem $hsePpeItem)
    {
        $hsePpeItem->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Detail APD berhasil diambil.',
            'data' => $hsePpeItem,
        ]);
    }

    public function update(
        Request $request,
        HsePpeItem $hsePpeItem
    ) {
        $validated = $request->validate([
            'category_id' => [
                'sometimes',
                'required',
                'exists:hse_ppe_categories,id',
            ],
            'kode_apd' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                'unique:hse_ppe_items,kode_apd,' . $hsePpeItem->id,
            ],
            'nama_apd' => [
                'sometimes',
                'required',
                'string',
                'max:150',
            ],
            'ukuran' => [
                'nullable',
                'string',
                'max:50',
            ],
            'satuan' => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],
            'lokasi_penyimpanan' => [
                'nullable',
                'string',
                'max:150',
            ],
            'stok_minimum' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'status' => [
                'boolean',
            ],
        ]);

        $hsePpeItem->update($validated);

        $hsePpeItem->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Data APD berhasil diperbarui.',
            'data' => $hsePpeItem,
        ]);
    }

    public function destroy(HsePpeItem $hsePpeItem)
    {
        $hsePpeItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data APD berhasil dihapus.',
        ]);
    }
}