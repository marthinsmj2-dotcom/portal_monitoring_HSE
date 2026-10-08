<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseChecklist extends Model
{
    protected $table = 'hse_checklists';

    protected $fillable = [
        'inspection_type_id',
        'nama_item',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function inspectionType(): BelongsTo
    {
        return $this->belongsTo(
            HseInspectionType::class,
            'inspection_type_id'
        );
    }

    public function results(): HasMany
    {
        return $this->hasMany(
            HseInspectionChecklistResult::class,
            'checklist_id'
        );
    }
}