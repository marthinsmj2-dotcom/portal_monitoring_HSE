<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseCorrectiveAction;
use Illuminate\Http\Request;

class HseCorrectiveActionController extends Controller
{
    public function index(Request $request)
    {
        $query = HseCorrectiveAction::with([
            'finding.category',
            'finding.riskLevel',
        ]);

        if ($request->filled('finding_id')) {
            $query->where('finding_id', $request->finding_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data tindakan perbaikan berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'finding_id' => [
                'required',
                'exists:hse_findings,id',
            ],
            'pic_employee_id' => [
                'nullable',
                'integer',
            ],
            'deskripsi_tindakan' => [
                'required',
                'string',
            ],
            'target_date' => [
                'nullable',
                'date',
            ],
            'progres' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            'status' => [
                'nullable',
                'in:OPEN,PROSES,CLOSE',
            ],
        ]);

        $data = HseCorrectiveAction::create([
            'finding_id' =>
                $validated['finding_id'],

            'pic_employee_id' =>
                $validated['pic_employee_id'] ?? null,

            'deskripsi_tindakan' =>
                $validated['deskripsi_tindakan'],

            'target_date' =>
                $validated['target_date'] ?? null,

            'progres' =>
                $validated['progres'] ?? 0,

            'status' =>
                $validated['status'] ?? 'OPEN',
        ]);

        $data->load([
            'finding.category',
            'finding.riskLevel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tindakan perbaikan berhasil dibuat.',
            'data' => $data,
        ], 201);
    }

    public function show(HseCorrectiveAction $hseCorrectiveAction)
    {
        $hseCorrectiveAction->load([
            'finding.category',
            'finding.riskLevel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail tindakan perbaikan berhasil diambil.',
            'data' => $hseCorrectiveAction,
        ]);
    }

    public function update(
        Request $request,
        HseCorrectiveAction $hseCorrectiveAction
    ) {
        $validated = $request->validate([
            'finding_id' => [
                'sometimes',
                'required',
                'exists:hse_findings,id',
            ],
            'pic_employee_id' => [
                'nullable',
                'integer',
            ],
            'deskripsi_tindakan' => [
                'sometimes',
                'required',
                'string',
            ],
            'target_date' => [
                'nullable',
                'date',
            ],
            'progres' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            'status' => [
                'sometimes',
                'required',
                'in:OPEN,PROSES,CLOSE',
            ],
        ]);

        $hseCorrectiveAction->update($validated);

        $hseCorrectiveAction->load([
            'finding.category',
            'finding.riskLevel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tindakan perbaikan berhasil diperbarui.',
            'data' => $hseCorrectiveAction,
        ]);
    }

    public function destroy(HseCorrectiveAction $hseCorrectiveAction)
    {
        $hseCorrectiveAction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tindakan perbaikan berhasil dihapus.',
        ]);
    }
}