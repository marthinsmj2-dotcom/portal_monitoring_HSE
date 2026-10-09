<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeItem;
use Illuminate\Http\Request;

class HsePpeStockController extends Controller
{
    private function stockSummary(HsePpeItem $item): array
    {
        $transactions = $item->stockTransactions();

        $stockIn = (int) (clone $transactions)
            ->where('jenis_transaksi', 'IN')
            ->sum('jumlah');

        $stockOut = (int) (clone $transactions)
            ->where('jenis_transaksi', 'OUT')
            ->sum('jumlah');

        $adjustmentIn = (int) (clone $transactions)
            ->where('jenis_transaksi', 'ADJUSTMENT')
            ->where('arah_penyesuaian', 'MENAMBAH')
            ->sum('jumlah');

        $adjustmentOut = (int) (clone $transactions)
            ->where('jenis_transaksi', 'ADJUSTMENT')
            ->where('arah_penyesuaian', 'MENGURANGI')
            ->sum('jumlah');

        $adjustment = $adjustmentIn - $adjustmentOut;

        $stockFinal = $stockIn
            - $stockOut
            + $adjustment;

        return [
            'stok_masuk' => $stockIn,
            'stok_keluar' => $stockOut,
            'penyesuaian_masuk' => $adjustmentIn,
            'penyesuaian_keluar' => $adjustmentOut,
            'penyesuaian' => $adjustment,
            'stok_akhir' => $stockFinal,
            'status_stok' => $stockFinal <= $item->stok_minimum
                ? 'MINIMUM'
                : 'AMAN',
        ];
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'sometimes',
                'integer',
                'exists:hse_ppe_categories,id',
            ],
            'status' => [
                'sometimes',
                'boolean',
            ],
        ]);

        $query = HsePpeItem::with('category');

        if (isset($validated['category_id'])) {
            $query->where(
                'category_id',
                $validated['category_id']
            );
        }

        if (isset($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        $data = $query->latest()
            ->get()
            ->map(function (HsePpeItem $item) {
                return array_merge([
                    'id' => $item->id,
                    'kode_apd' => $item->kode_apd,
                    'nama_apd' => $item->nama_apd,
                    'kategori' => $item->category?->nama_kategori,
                    'ukuran' => $item->ukuran,
                    'satuan' => $item->satuan,
                    'lokasi_penyimpanan' =>
                        $item->lokasi_penyimpanan,
                    'stok_minimum' => $item->stok_minimum,
                    'status' => $item->status,
                ], $this->stockSummary($item));
            });

        return response()->json([
            'success' => true,
            'message' => 'Monitoring stok APD berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(HsePpeItem $hsePpeItem)
    {
        $hsePpeItem->load('category');

        $data = array_merge([
            'id' => $hsePpeItem->id,
            'kode_apd' => $hsePpeItem->kode_apd,
            'nama_apd' => $hsePpeItem->nama_apd,
            'kategori' => $hsePpeItem->category?->nama_kategori,
            'ukuran' => $hsePpeItem->ukuran,
            'satuan' => $hsePpeItem->satuan,
            'lokasi_penyimpanan' =>
                $hsePpeItem->lokasi_penyimpanan,
            'stok_minimum' => $hsePpeItem->stok_minimum,
            'status' => $hsePpeItem->status,
        ], $this->stockSummary($hsePpeItem));

        return response()->json([
            'success' => true,
            'message' => 'Detail stok APD berhasil diambil.',
            'data' => $data,
        ]);
    }
}