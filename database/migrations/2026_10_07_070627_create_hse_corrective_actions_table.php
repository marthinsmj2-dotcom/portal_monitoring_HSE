<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_corrective_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('finding_id')
                ->constrained('hse_findings')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('pic_employee_id')->nullable();

            $table->text('deskripsi_tindakan');

            $table->date('target_date')->nullable();

            $table->unsignedTinyInteger('progres')->default(0);

            $table->string('status', 30)->default('OPEN');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_corrective_actions');
    }
};