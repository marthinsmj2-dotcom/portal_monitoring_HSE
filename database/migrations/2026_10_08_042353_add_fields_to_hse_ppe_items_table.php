<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hse_ppe_items', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('id');

            $table->string('kode_apd', 50)
                ->unique()
                ->after('category_id');

            $table->string('nama_apd', 150)
                ->after('kode_apd');

            $table->string('ukuran', 50)
                ->nullable()
                ->after('nama_apd');

            $table->string('satuan', 50)
                ->after('ukuran');

            $table->string('lokasi_penyimpanan', 150)
                ->nullable()
                ->after('satuan');

            $table->unsignedInteger('stok_minimum')
                ->default(0)
                ->after('lokasi_penyimpanan');

            $table->boolean('status')
                ->default(true)
                ->after('stok_minimum');

            $table->foreign('category_id')
                ->references('id')
                ->on('hse_ppe_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hse_ppe_items', function (Blueprint $table) {
            $table->dropForeign([
                'category_id',
            ]);

            $table->dropColumn([
                'category_id',
                'kode_apd',
                'nama_apd',
                'ukuran',
                'satuan',
                'lokasi_penyimpanan',
                'stok_minimum',
                'status',
            ]);
        });
    }
};