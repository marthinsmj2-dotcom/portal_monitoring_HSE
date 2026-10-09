<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeItem;
use App\Models\HsePpeStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HsePpeStockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'ppe_item_id' => [
                'sometimes',
                'integer',
                'exists:hse_ppe_items,id',
            ],
            'jenis_transaksi' => [
                'sometimes',
                'in:IN,OUT,ADJUSTMENT',
            ],
        ]);

        $query = HsePpeStockTransaction::with([
            'ppeItem.category',
            'createdBy',
        ]);

        if (isset($validated['ppe_item_id'])) {
            $query->where(
                'ppe_item_id',
                $validated['ppe_item_id']
            );
        }

        if (isset($validated['jenis_transaksi'])) {
            $query->where(
                'jenis_transaksi',
                $validated['jenis_transaksi']
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi stok APD berhasil diambil.',
            'data' => $query->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ppe_item_id' => [
                'required',
                'integer',
                'exists:hse_ppe_items,id',
            ],
            'jenis_transaksi' => [
                'required',
                'in:IN,OUT,ADJUSTMENT',
            ],
            'arah_penyesuaian' => [
                'required_if:jenis_transaksi,ADJUSTMENT',
                'nullable',
                'in:MENAMBAH,MENGURANGI',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
            'tanggal_transaksi' => [
                'required',
                'date',
            ],
            'recipient_employee_id' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'reference_no' => [
                'nullable',
                'string',
                'max:100',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        if ($validated['jenis_transaksi'] !== 'ADJUSTMENT') {
            $validated['arah_penyesuaian'] = null;
        }

        $data = DB::transaction(function () use ($validated) {
            $item = HsePpeItem::query()
                ->whereKey($validated['ppe_item_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stock = $item->stockTransactions()
                ->selectRaw("
                    COALESCE(SUM(
                        CASE
                            WHEN jenis_transaksi = 'IN'
                                THEN jumlah
                            WHEN jenis_transaksi = 'OUT'
                                THEN -jumlah
                            WHEN jenis_transaksi = 'ADJUSTMENT'
                                AND arah_penyesuaian = 'MENAMBAH'
                                THEN jumlah
                            WHEN jenis_transaksi = 'ADJUSTMENT'
                                AND arah_penyesuaian = 'MENGURANGI'
                                THEN -jumlah
                            ELSE 0
                        END
                    ), 0) AS stok_akhir
                ")
                ->value('stok_akhir');

            $stokAkhir = (int) $stock;
            $jumlah = (int) $validated['jumlah'];

            $pengurangan =
                $validated['jenis_transaksi'] === 'OUT'
                || (
                    $validated['jenis_transaksi'] === 'ADJUSTMENT'
                    && $validated['arah_penyesuaian'] === 'MENGURANGI'
                );

            if ($pengurangan && $jumlah > $stokAkhir) {
                throw ValidationException::withMessages([
                    'jumlah' => [
                        "Stok tidak mencukupi. Stok tersedia: {$stokAkhir}.",
                    ],
                ]);
            }

            return HsePpeStockTransaction::create([
                'ppe_item_id' => $item->id,
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'arah_penyesuaian' =>
                    $validated['arah_penyesuaian'] ?? null,
                'jumlah' => $jumlah,
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'recipient_employee_id' =>
                    $validated['recipient_employee_id'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'created_by' => null,
            ]);
        }, 3);

        $data->load(['ppeItem.category', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi stok APD berhasil dibuat.',
            'data' => $data,
        ], 201);
    }

    public function show(
        HsePpeStockTransaction $hsePpeStockTransaction
    ) {
        $hsePpeStockTransaction->load([
            'ppeItem.category',
            'createdBy',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi stok APD berhasil diambil.',
            'data' => $hsePpeStockTransaction,
        ]);
    }

    public function update(
        HsePpeStockTransaction $hsePpeStockTransaction
    ) {
        return response()->json([
            'success' => false,
            'message' =>
                'Transaksi tidak dapat diedit. Buat transaksi koreksi baru.',
        ], 405);
    }

    public function destroy(
        HsePpeStockTransaction $hsePpeStockTransaction
    ) {
        return response()->json([
            'success' => false,
            'message' =>
                'Transaksi tidak dapat dihapus melalui API ini.',
        ], 405);
    }
}