<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HseInspectionChecklistResult extends Model
{
    protected $table = 'hse_inspection_checklist_results';

    protected $fillable = [
        'inspection_id',
        'checklist_id',
        'hasil',
        'catatan',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(
            HseInspection::class,
            'inspection_id'
        );
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(
            HseChecklist::class,
            'checklist_id'
        );
    }
}