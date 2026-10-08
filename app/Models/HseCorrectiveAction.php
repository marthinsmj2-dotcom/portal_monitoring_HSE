<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HseCorrectiveAction extends Model
{
    protected $table = 'hse_corrective_actions';

    protected $fillable = [
        'finding_id',
        'pic_employee_id',
        'deskripsi_tindakan',
        'target_date',
        'progres',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'target_date' => 'date',
        'progres' => 'integer',
        'verified_at' => 'datetime',
    ];

    public function finding(): BelongsTo
    {
        return $this->belongsTo(
            HseFinding::class,
            'finding_id'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}