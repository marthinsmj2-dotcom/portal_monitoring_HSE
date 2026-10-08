<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseChecklist;
use Illuminate\Http\Request;

class HseChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = HseChecklist::with('inspectionType');

        if ($request->filled('inspection_type_id')) {
            $query->where(
                'inspection_type_id',
                $request->inspection_type_id
            );
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data checklist berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_type_id' => 'required|exists:hse_inspection_types,id',
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = HseChecklist::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(HseChecklist $hseChecklist)
    {
        $hseChecklist->load('inspectionType');

        return response()->json([
            'success' => true,
            'message' => 'Detail checklist berhasil diambil.',
            'data' => $hseChecklist,
        ]);
    }

    public function update(
        Request $request,
        HseChecklist $hseChecklist
    ) {
        $validated = $request->validate([
            'inspection_type_id' => 'sometimes|required|exists:hse_inspection_types,id',
            'nama_item' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $hseChecklist->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil diperbarui.',
            'data' => $hseChecklist->fresh(),
        ]);
    }

    public function destroy(HseChecklist $hseChecklist)
    {
        $hseChecklist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil dihapus.',
        ]);
    }
}