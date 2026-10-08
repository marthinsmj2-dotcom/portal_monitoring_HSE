<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HseCorrectiveAction;
use App\Models\HseFinding;
use App\Models\HseInspection;
use App\Models\HsePpeItem;
use Illuminate\Http\Request;

class HseReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ]);

        $tanggalMulai = $validated['tanggal_mulai'] ?? null;
        $tanggalSelesai = $validated['tanggal_selesai'] ?? null;

        $inspections = HseInspection::with([
            'inspectionType',
            'findings.category',
            'findings.riskLevel',
        ])
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate(
                    'tanggal_inspeksi',
                    '>=',
                    $tanggalMulai
                );
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate(
                    'tanggal_inspeksi',
                    '<=',
                    $tanggalSelesai
                );
            })
            ->latest('tanggal_inspeksi')
            ->get();

        $findings = HseFinding::with([
            'category',
            'riskLevel',
            'correctiveActions',
        ])
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $tanggalMulai
                );
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $tanggalSelesai
                );
            })
            ->latest()
            ->get();

        $correctiveActions = HseCorrectiveAction::with([
            'finding.category',
            'finding.riskLevel',
        ])
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $tanggalMulai
                );
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $tanggalSelesai
                );
            })
            ->latest()
            ->get();

        $ppeItems = HsePpeItem::with('category')
            ->latest()
            ->get()
            ->map(function ($item) {

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
                    'stok_minimum' => $item->stok_minimum,
                    'stok_akhir' => $stockFinal,
                    'status_stok' =>
                        $stockFinal <= $item->stok_minimum
                            ? 'MINIMUM'
                            : 'AMAN',
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Laporan HSE berhasil diambil.',
            'filter' => [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
            ],
            'ringkasan' => [
                'total_inspeksi' => $inspections->count(),
                'total_temuan' => $findings->count(),
                'temuan_open' => $findings
                    ->where('status', 'OPEN')
                    ->count(),
                'temuan_proses' => $findings
                    ->where('status', 'PROSES')
                    ->count(),
                'temuan_close' => $findings
                    ->where('status', 'CLOSE')
                    ->count(),
                'total_tindakan_perbaikan' =>
                    $correctiveActions->count(),
            ],
            'data' => [
                'inspeksi' => $inspections,
                'temuan' => $findings,
                'tindakan_perbaikan' => $correctiveActions,
                'stok_apd' => $ppeItems,
            ],
        ]);
    }
}