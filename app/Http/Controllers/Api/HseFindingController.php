<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseFinding;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HseFindingController extends Controller
{
    /**
     * Menampilkan seluruh temuan HSE.
     */
    public function index()
    {
        $findings = HseFinding::with([
            'category',
            'riskLevel',
            'correctiveActions',
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $findings,
        ]);
    }

    /**
     * Menyimpan temuan HSE.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_id' => [
                'nullable',
                'exists:hse_inspections,id',
            ],
            'category_id' => [
                'required',
                'exists:hse_finding_categories,id',
            ],
            'risk_level_id' => [
                'required',
                'exists:hse_risk_levels,id',
            ],
            'location_id' => [
                'nullable',
                'integer',
            ],
            'pic_employee_id' => [
                'nullable',
                'integer',
            ],
            'deskripsi_temuan' => [
                'required',
                'string',
            ],
        ]);

        $validated['status'] = 'OPEN';
       $validated['created_by'] = \Illuminate\Support\Facades\Auth::id();

        $finding = HseFinding::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Temuan HSE berhasil ditambahkan.',
            'data' => $finding->load([
                'category',
                'riskLevel',
                'correctiveActions',
            ]),
        ], 201);
    }

    /**
     * Menampilkan detail temuan.
     */
    public function show(HseFinding $hseFinding)
    {
        return response()->json([
            'success' => true,
            'data' => $hseFinding->load([
                'category',
                'riskLevel',
                'correctiveActions',
            ]),
        ]);
    }

    /**
     * Memperbarui data temuan tanpa mengubah status.
     */
    public function update(
        Request $request,
        HseFinding $hseFinding
    ) {
        $validated = $request->validate([
            'inspection_id' => [
                'sometimes',
                'nullable',
                'exists:hse_inspections,id',
            ],
            'category_id' => [
                'sometimes',
                'required',
                'exists:hse_finding_categories,id',
            ],
            'risk_level_id' => [
                'sometimes',
                'required',
                'exists:hse_risk_levels,id',
            ],
            'location_id' => [
                'sometimes',
                'nullable',
                'integer',
            ],
            'pic_employee_id' => [
                'sometimes',
                'nullable',
                'integer',
            ],
            'deskripsi_temuan' => [
                'sometimes',
                'required',
                'string',
            ],
        ]);

        if ($hseFinding->status === 'CLOSE') {
            return response()->json([
                'success' => false,
                'message' => 'Temuan yang sudah CLOSE tidak dapat diubah.',
            ], 422);
        }

        $hseFinding->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Temuan HSE berhasil diperbarui.',
            'data' => $hseFinding->fresh([
                'category',
                'riskLevel',
                'correctiveActions',
            ]),
        ]);
    }

    /**
     * Menghapus temuan HSE.
     */
    public function destroy(HseFinding $hseFinding)
    {
        if ($hseFinding->status === 'CLOSE') {
            return response()->json([
                'success' => false,
                'message' => 'Temuan yang sudah CLOSE tidak dapat dihapus.',
            ], 422);
        }

        $hseFinding->delete();

        return response()->json([
            'success' => true,
            'message' => 'Temuan HSE berhasil dihapus.',
        ]);
    }

    /**
     * Memverifikasi perubahan status temuan.
     */
    public function verify(
        Request $request,
        HseFinding $hseFinding
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['OPEN', 'PROSES', 'CLOSE']),
            ],
        ]);

        $currentStatus = $hseFinding->status;
        $newStatus = $validated['status'];

        // OPEN hanya dapat menjadi PROSES.
        if (
            $currentStatus === 'OPEN'
            && $newStatus !== 'PROSES'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Temuan OPEN hanya dapat diubah menjadi PROSES.',
            ], 422);
        }

        // PROSES hanya dapat menjadi CLOSE.
        if (
            $currentStatus === 'PROSES'
            && $newStatus !== 'CLOSE'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Temuan PROSES hanya dapat diubah menjadi CLOSE.',
            ], 422);
        }

        // Temuan yang sudah ditutup tidak dapat diubah lagi.
        if ($currentStatus === 'CLOSE') {
            return response()->json([
                'success' => false,
                'message' => 'Temuan yang sudah CLOSE tidak dapat diubah lagi.',
            ], 422);
        }

        // Periksa tindakan perbaikan sebelum menutup temuan.
        if ($newStatus === 'CLOSE') {
            $actions = $hseFinding->correctiveActions()->get();

            if ($actions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Temuan belum dapat ditutup karena belum memiliki tindakan perbaikan.',
                ], 422);
            }

            $hasUnfinishedAction = $actions->contains(
                function ($action) {
                    return $action->status !== 'CLOSE'
                        || (int) $action->progres < 100
                        || $action->verified_at === null;
                }
            );

            if ($hasUnfinishedAction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selesaikan dan verifikasi seluruh tindakan perbaikan sebelum menutup temuan.',
                ], 422);
            }
        }

        $hseFinding->update([
            'status' => $newStatus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status temuan berhasil diperbarui.',
            'data' => $hseFinding->fresh([
                'category',
                'riskLevel',
                'correctiveActions',
            ]),
        ]);
    }
}