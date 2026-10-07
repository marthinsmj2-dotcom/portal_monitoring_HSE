<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_checklists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_type_id')
                ->constrained('hse_inspection_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_checklists');
    }
};