<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseFindingCategory extends Model
{
    protected $table = 'hse_finding_categories';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function findings(): HasMany
    {
        return $this->hasMany(
            HseFinding::class,
            'category_id'
        );
    }
}