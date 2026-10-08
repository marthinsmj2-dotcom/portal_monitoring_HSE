<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseFinding extends Model
{
    protected $table = 'hse_findings';

    protected $fillable = [
        'inspection_id',
        'category_id',
        'risk_level_id',
        'location_id',
        'pic_employee_id',
        'deskripsi_temuan',
        'status',
        'created_by',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            HseInspection::class,
            'inspection_id'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            HseFindingCategory::class,
            'category_id'
        );
    }

    public function riskLevel(): BelongsTo
    {
        return $this->belongsTo(
            HseRiskLevel::class,
            'risk_level_id'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function correctiveActions(): HasMany
    {
        return $this->hasMany(
            HseCorrectiveAction::class,
            'finding_id'
        );
    }
}