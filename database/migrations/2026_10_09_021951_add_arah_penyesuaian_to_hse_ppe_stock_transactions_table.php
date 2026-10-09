<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hse_ppe_stock_transactions', function (Blueprint $table) {
            $table->enum('arah_penyesuaian', [
                'MENAMBAH',
                'MENGURANGI',
            ])->nullable()->after('jenis_transaksi');
        });

        // Pertahankan perhitungan transaksi ADJUSTMENT lama.
        DB::table('hse_ppe_stock_transactions')
            ->where('jenis_transaksi', 'ADJUSTMENT')
            ->update([
                'arah_penyesuaian' => 'MENAMBAH',
            ]);
    }

    public function down(): void
    {
        Schema::table('hse_ppe_stock_transactions', function (Blueprint $table) {
            $table->dropColumn('arah_penyesuaian');
        });
    }
};