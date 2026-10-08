<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HsePpeStockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = HsePpeStockTransaction::with([
            'ppeItem.category',
            'createdBy',
        ]);

        if ($request->filled('ppe_item_id')) {
            $query->where('ppe_item_id', $request->ppe_item_id);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where(
                'jenis_transaksi',
                $request->jenis_transaksi
            );
        }

        $data = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi stok APD berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ppe_item_id' => [
                'required',
                'exists:hse_ppe_items,id',
            ],
            'jenis_transaksi' => [
                'required',
                'in:IN,OUT,ADJUSTMENT',
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
            'created_by' => [
                'nullable',
                'exists:users,id',
            ],
        ]);

        $data = DB::transaction(function () use ($validated) {
            $stockTransaction = HsePpeStockTransaction::create([
                'ppe_item_id' =>
                    $validated['ppe_item_id'],

                'jenis_transaksi' =>
                    $validated['jenis_transaksi'],

                'jumlah' =>
                    $validated['jumlah'],

                'tanggal_transaksi' =>
                    $validated['tanggal_transaksi'],

                'recipient_employee_id' =>
                    $validated['recipient_employee_id'] ?? null,

                'reference_no' =>
                    $validated['reference_no'] ?? null,

                'keterangan' =>
                    $validated['keterangan'] ?? null,

                'created_by' =>
                    $validated['created_by'] ?? null,
            ]);

            return $stockTransaction;
        });

        $data->load([
            'ppeItem.category',
            'createdBy',
        ]);

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
        Request $request,
        HsePpeStockTransaction $hsePpeStockTransaction
    ) {
        $validated = $request->validate([
            'jenis_transaksi' => [
                'sometimes',
                'required',
                'in:IN,OUT,ADJUSTMENT',
            ],
            'jumlah' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
            ],
            'tanggal_transaksi' => [
                'sometimes',
                'required',
                'date',
            ],
            'recipient_employee_id' => [
                'nullable',
                'integer',
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

        $hsePpeStockTransaction->update($validated);

        $hsePpeStockTransaction->load([
            'ppeItem.category',
            'createdBy',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi stok APD berhasil diperbarui.',
            'data' => $hsePpeStockTransaction,
        ]);
    }

    public function destroy(
        HsePpeStockTransaction $hsePpeStockTransaction
    ) {
        $hsePpeStockTransaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi stok APD berhasil dihapus.',
        ]);
    }
}