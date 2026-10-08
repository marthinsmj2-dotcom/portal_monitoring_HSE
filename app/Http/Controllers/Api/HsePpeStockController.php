<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HsePpeItem;
use Illuminate\Http\Request;

class HsePpeStockController extends Controller
{
    public function index(Request $request)
    {
        $query = HsePpeItem::with('category');

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $items = $query
            ->latest()
            ->get();

        $data = $items->map(function ($item) {
            $stockIn = $item->stockTransactions()
                ->where('jenis_transaksi', 'IN')
                ->sum('jumlah');

            $stockOut = $item->stockTransactions()
                ->where('jenis_transaksi', 'OUT')
                ->sum('jumlah');

            $adjustment = $item->stockTransactions()
                ->where('jenis_transaksi', 'ADJUSTMENT')
                ->sum('jumlah');

            $stockFinal = $stockIn - $stockOut + $adjustment;

            return [
                'id' => $item->id,
                'kode_apd' => $item->kode_apd,
                'nama_apd' => $item->nama_apd,
                'kategori' => $item->category?->nama_kategori,
                'ukuran' => $item->ukuran,
                'satuan' => $item->satuan,
                'lokasi_penyimpanan' =>
                    $item->lokasi_penyimpanan,
                'stok_minimum' => $item->stok_minimum,
                'stok_masuk' => $stockIn,
                'stok_keluar' => $stockOut,
                'penyesuaian' => $adjustment,
                'stok_akhir' => $stockFinal,
                'status_stok' =>
                    $stockFinal <= $item->stok_minimum
                        ? 'MINIMUM'
                        : 'AMAN',
                'status' => $item->status,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Monitoring stok APD berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(HsePpeItem $hsePpeItem)
    {
        $stockIn = $hsePpeItem->stockTransactions()
            ->where('jenis_transaksi', 'IN')
            ->sum('jumlah');

        $stockOut = $hsePpeItem->stockTransactions()
            ->where('jenis_transaksi', 'OUT')
            ->sum('jumlah');

        $adjustment = $hsePpeItem->stockTransactions()
            ->where('jenis_transaksi', 'ADJUSTMENT')
            ->sum('jumlah');

        $stockFinal = $stockIn - $stockOut + $adjustment;

        return response()->json([
            'success' => true,
            'message' => 'Detail stok APD berhasil diambil.',
            'data' => [
                'id' => $hsePpeItem->id,
                'kode_apd' => $hsePpeItem->kode_apd,
                'nama_apd' => $hsePpeItem->nama_apd,
                'kategori' =>
                    $hsePpeItem->category?->nama_kategori,
                'stok_minimum' =>
                    $hsePpeItem->stok_minimum,
                'stok_masuk' => $stockIn,
                'stok_keluar' => $stockOut,
                'penyesuaian' => $adjustment,
                'stok_akhir' => $stockFinal,
                'status_stok' =>
                    $stockFinal <= $hsePpeItem->stok_minimum
                        ? 'MINIMUM'
                        : 'AMAN',
            ],
        ]);
    }
}