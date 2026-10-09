<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HsePpeStockTransaction extends Model
{
    protected $table = 'hse_ppe_stock_transactions';

    protected $fillable = [
        'ppe_item_id',
        'jenis_transaksi',
        'arah_penyesuaian',
        'jumlah',
        'tanggal_transaksi',
        'recipient_employee_id',
        'reference_no',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tanggal_transaksi' => 'date',
    ];

    public function ppeItem(): BelongsTo
    {
        return $this->belongsTo(
            HsePpeItem::class,
            'ppe_item_id'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}