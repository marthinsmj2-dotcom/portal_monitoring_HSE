<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('aksi', 50);

            $table->string('modul', 100);

            $table->string('tabel', 100)->nullable();

            $table->unsignedBigInteger('record_id')->nullable();

            $table->text('deskripsi')->nullable();

            $table->json('data_lama')->nullable();

            $table->json('data_baru')->nullable();

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index([
                'modul',
                'aksi',
            ]);

            $table->index([
                'tabel',
                'record_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_audit_logs');
    }
};