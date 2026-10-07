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
        Schema::create('hse_findings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->nullable()
                ->constrained('hse_inspections')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('hse_finding_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('risk_level_id')
                ->nullable()
                ->constrained('hse_risk_levels')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unsignedBigInteger('location_id')->nullable();

            $table->unsignedBigInteger('pic_employee_id')->nullable();

            $table->text('deskripsi_temuan');

            $table->string('status', 30)->default('OPEN');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hse_findings');
    }
};