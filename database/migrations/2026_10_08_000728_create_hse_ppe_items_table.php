<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hse_ppe_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('hse_ppe_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('kode_apd', 50)->unique();
            $table->string('nama_apd', 150);
            $table->string('ukuran', 50)->nullable();
            $table->string('satuan', 50);
            $table->string('lokasi_penyimpanan', 150)->nullable();
            $table->unsignedInteger('stok_minimum')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hse_ppe_items');
    }
};