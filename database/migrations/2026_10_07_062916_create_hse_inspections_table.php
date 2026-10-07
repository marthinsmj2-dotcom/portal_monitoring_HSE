<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_inspections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_type_id')
                ->constrained('hse_inspection_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('inspector_employee_id')->nullable();

            $table->date('tanggal_inspeksi');

            $table->string('status', 30)->default('OPEN');

            $table->text('catatan')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_inspections');
    }
};