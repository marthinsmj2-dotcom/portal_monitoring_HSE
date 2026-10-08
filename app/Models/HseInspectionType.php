<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseInspectionType extends Model
{
    protected $table = 'hse_inspection_types';

    protected $fillable = [
        'nama_jenis_inspeksi',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function checklists(): HasMany
    {
        return $this->hasMany(HseChecklist::class, 'inspection_type_id');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(HseInspection::class, 'inspection_type_id');
    }
}