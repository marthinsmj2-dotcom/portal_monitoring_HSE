<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_inspection_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis_inspeksi', 150);
            $table->text('deskripsi')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_inspection_types');
    }
};