<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseCorrectiveAction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HseCorrectiveActionController extends Controller
{
    private function relations(): array
    {
        return [
            'finding.category',
            'finding.riskLevel',
            'verifiedBy',
        ];
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'finding_id' => [
                'sometimes',
                'integer',
                'exists:hse_findings,id',
            ],
            'status' => [
                'sometimes',
                'string',
                Rule::in(['OPEN', 'PROSES', 'CLOSE']),
            ],
        ]);

        $query = HseCorrectiveAction::with($this->relations());

        if (isset($validated['finding_id'])) {
            $query->where('finding_id', $validated['finding_id']);
        }

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data tindakan perbaikan berhasil diambil.',
            'data' => $query->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'finding_id' => [
                'required',
                'integer',
                'exists:hse_findings,id',
            ],
            'pic_employee_id' => [
                'nullable',
                'integer',
                'min:1',
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
                'sometimes',
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['OPEN', 'PROSES', 'CLOSE']),
            ],
        ]);

        $progres = $validated['progres'] ?? 0;
        $status = $validated['status'] ?? 'OPEN';

          if ($status === 'CLOSE' && (int) $progres !== 100) {
            return response()->json([
                'success' => false,
                'message' => 'Progres harus 100% sebelum tindakan ditutup.',
            ], 422);
        }

        $data = HseCorrectiveAction::create([
            'finding_id' => $validated['finding_id'],
            'pic_employee_id' => $validated['pic_employee_id'] ?? null,
            'deskripsi_tindakan' => $validated['deskripsi_tindakan'],
            'target_date' => $validated['target_date'] ?? null,
            'progres' => $progres,
            'status' => $status,
        ]);

        $data->load($this->relations());

        return response()->json([
            'success' => true,
            'message' => 'Tindakan perbaikan berhasil dibuat.',
            'data' => $data,
        ], 201);
    }

    public function show(HseCorrectiveAction $hseCorrectiveAction)
    {
        $hseCorrectiveAction->load($this->relations());

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
                'integer',
                'exists:hse_findings,id',
            ],
            'pic_employee_id' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
            ],
            'deskripsi_tindakan' => [
                'sometimes',
                'required',
                'string',
            ],
            'target_date' => [
                'sometimes',
                'nullable',
                'date',
            ],
            'progres' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['OPEN', 'PROSES', 'CLOSE']),
            ],
        ]);

        $progres = $validated['progres'] ?? $hseCorrectiveAction->progres;
        $status = $validated['status'] ?? $hseCorrectiveAction->status;

        if ($status === 'CLOSE' && (int) $progres !== 100) {
            return response()->json([
                'success' => false,
                'message' => 'Progres harus 100% sebelum tindakan ditutup.',
            ], 422);
        }

        $hseCorrectiveAction->update($validated);
        $hseCorrectiveAction->load($this->relations());

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