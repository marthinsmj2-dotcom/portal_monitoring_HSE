<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseInspection extends Model
{
    protected $table = 'hse_inspections';

    protected $fillable = [
        'inspection_type_id',
        'location_id',
        'inspector_employee_id',
        'tanggal_inspeksi',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_inspeksi' => 'date',
    ];

    public function inspectionType(): BelongsTo
    {
        return $this->belongsTo(
            HseInspectionType::class,
            'inspection_type_id'
        );
    }

    public function checklistResults(): HasMany
    {
        return $this->hasMany(
            HseInspectionChecklistResult::class,
            'inspection_id'
        );
    }

    public function findings(): HasMany
    {
        return $this->hasMany(
            HseFinding::class,
            'inspection_id'
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