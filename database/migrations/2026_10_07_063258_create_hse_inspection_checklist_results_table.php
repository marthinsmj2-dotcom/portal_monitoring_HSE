<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_inspection_checklist_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->constrained('hse_inspections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('checklist_id')
                ->constrained('hse_checklists')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('hasil', 30);
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(
                ['inspection_id', 'checklist_id'],
                'inspection_checklist_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_inspection_checklist_results');
    }
};