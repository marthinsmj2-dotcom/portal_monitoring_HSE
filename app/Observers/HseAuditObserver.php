<?php

namespace App\Observers;

use App\Models\HseAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HseAuditObserver
{
    public function created(Model $model): void
    {
        $this->writeLog($model, 'CREATE', null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->writeLog(
            $model,
            'UPDATE',
            $model->getRawOriginal(),
            $model->getChanges()
        );
    }

    public function deleted(Model $model): void
    {
        $this->writeLog(
            $model,
            'DELETE',
            $model->getAttributes(),
            null
        );
    }

    private function writeLog(
        Model $model,
        string $aksi,
        ?array $dataLama,
        ?array $dataBaru
    ): void {
        // Hindari audit log mencatat dirinya sendiri.
        if ($model instanceof HseAuditLog) {
            return;
        }

        HseAuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'modul' => $this->getModuleName($model),
            'tabel' => $model->getTable(),
            'record_id' => $model->getKey(),
            'deskripsi' => $aksi . ' data pada tabel ' . $model->getTable(),
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    private function getModuleName(Model $model): string
    {
        return match ($model->getTable()) {
            'hse_inspection_types',
            'hse_checklists',
            'hse_inspections',
            'hse_inspection_checklist_results' => 'Kegiatan HSE',

            'hse_finding_categories',
            'hse_risk_levels',
            'hse_findings',
            'hse_corrective_actions' => 'Temuan',

            'hse_ppe_categories',
            'hse_ppe_items',
            'hse_ppe_stock_transactions' => 'APD',

            default => 'HSE',
        };
    }
}