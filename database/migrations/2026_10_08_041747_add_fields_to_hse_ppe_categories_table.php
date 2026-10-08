<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hse_ppe_categories', function (Blueprint $table) {
            $table->string('nama_kategori', 150)->after('id');
            $table->text('deskripsi')->nullable()->after('nama_kategori');
            $table->boolean('status')->default(true)->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('hse_ppe_categories', function (Blueprint $table) {
            $table->dropColumn([
                'nama_kategori',
                'deskripsi',
                'status',
            ]);
        });
    }
};