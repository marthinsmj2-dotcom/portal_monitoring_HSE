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
        Schema::create('hse_ppe_stock_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ppe_item_id')
                ->constrained('hse_ppe_items')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('jenis_transaksi', 20);

            $table->unsignedInteger('jumlah');

            $table->date('tanggal_transaksi');

            $table->unsignedBigInteger('recipient_employee_id')->nullable();

            $table->string('reference_no', 100)->nullable();

            $table->text('keterangan')->nullable();

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
        Schema::dropIfExists('hse_ppe_stock_transactions');
    }
};