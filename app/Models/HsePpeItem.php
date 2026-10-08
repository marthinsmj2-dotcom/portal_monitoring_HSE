<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HsePpeItem extends Model
{
    protected $table = 'hse_ppe_items';

    protected $fillable = [
        'category_id',
        'kode_apd',
        'nama_apd',
        'ukuran',
        'satuan',
        'lokasi_penyimpanan',
        'stok_minimum',
        'status',
    ];

    protected $casts = [
        'stok_minimum' => 'integer',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            HsePpeCategory::class,
            'category_id'
        );
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(
            HsePpeStockTransaction::class,
            'ppe_item_id'
        );
    }
}