<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseRiskLevel extends Model
{
    protected $table = 'hse_risk_levels';

    protected $fillable = [
        'nama',
        'skor_min',
        'skor_max',
        'deskripsi',
    ];

    protected $casts = [
        'skor_min' => 'integer',
        'skor_max' => 'integer',
    ];

    public function findings(): HasMany
    {
        return $this->hasMany(
            HseFinding::class,
            'risk_level_id'
        );
    }
}