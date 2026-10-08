<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HseInspectionController extends Controller
{
    public function index(Request $request)
    {
        $query = HseInspection::with([
            'inspectionType',
            'checklistResults.checklist',
            'findings',
        ]);

        if ($request->filled('inspection_type_id')) {
            $query->where(
                'inspection_type_id',
                $request->inspection_type_id
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_inspeksi')) {
            $query->where(
                'tanggal_inspeksi',
                $request->tanggal_inspeksi
            );
        }

        $data = $query
            ->latest('tanggal_inspeksi')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data inspeksi berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_type_id' => [
                'required',
                'exists:hse_inspection_types,id',
            ],
            'location_id' => [
                'nullable',
                'integer',
            ],
            'inspector_employee_id' => [
                'nullable',
                'integer',
            ],
            'tanggal_inspeksi' => [
                'required',
                'date',
            ],
            'status' => [
                'nullable',
                'in:OPEN,PROSES,CLOSE',
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
            'created_by' => [
                'nullable',
                'exists:users,id',
            ],
            'checklist_results' => [
                'nullable',
                'array',
            ],
            'checklist_results.*.checklist_id' => [
                'required',
                'exists:hse_checklists,id',
            ],
            'checklist_results.*.hasil' => [
                'required',
                'string',
                'max:30',
            ],
            'checklist_results.*.catatan' => [
                'nullable',
                'string',
            ],
        ]);

        $inspection = DB::transaction(function () use ($validated) {

            $inspection = HseInspection::create([
                'inspection_type_id' =>
                    $validated['inspection_type_id'],

                'location_id' =>
                    $validated['location_id'] ?? null,

                'inspector_employee_id' =>
                    $validated['inspector_employee_id'] ?? null,

                'tanggal_inspeksi' =>
                    $validated['tanggal_inspeksi'],

                'status' =>
                    $validated['status'] ?? 'OPEN',

                'catatan' =>
                    $validated['catatan'] ?? null,

                'created_by' =>
                    $validated['created_by'] ?? null,
            ]);

            foreach (
                $validated['checklist_results'] ?? []
                as $result
            ) {
                $inspection->checklistResults()->create([
                    'checklist_id' =>
                        $result['checklist_id'],

                    'hasil' =>
                        $result['hasil'],

                    'catatan' =>
                        $result['catatan'] ?? null,
                ]);
            }

            return $inspection;
        });

        $inspection->load([
            'inspectionType',
            'checklistResults.checklist',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inspeksi berhasil dibuat.',
            'data' => $inspection,
        ], 201);
    }

    public function show(HseInspection $hseInspection)
    {
        $hseInspection->load([
            'inspectionType',
            'checklistResults.checklist',
            'findings',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail inspeksi berhasil diambil.',
            'data' => $hseInspection,
        ]);
    }

    public function update(
        Request $request,
        HseInspection $hseInspection
    ) {
        $validated = $request->validate([
            'inspection_type_id' => [
                'sometimes',
                'required',
                'exists:hse_inspection_types,id',
            ],
            'location_id' => [
                'nullable',
                'integer',
            ],
            'inspector_employee_id' => [
                'nullable',
                'integer',
            ],
            'tanggal_inspeksi' => [
                'sometimes',
                'required',
                'date',
            ],
            'status' => [
                'sometimes',
                'required',
                'in:OPEN,PROSES,CLOSE',
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
        ]);

        $hseInspection->update($validated);

        $hseInspection->load([
            'inspectionType',
            'checklistResults.checklist',
            'findings',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inspeksi berhasil diperbarui.',
            'data' => $hseInspection,
        ]);
    }

    public function destroy(HseInspection $hseInspection)
    {
        $hseInspection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inspeksi berhasil dihapus.',
        ]);
    }
}