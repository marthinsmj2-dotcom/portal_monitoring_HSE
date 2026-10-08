<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\HseChecklist;
use App\Models\HseCorrectiveAction;
use App\Models\HseFinding;
use App\Models\HseFindingCategory;
use App\Models\HseInspection;
use App\Models\HseInspectionChecklistResult;
use App\Models\HseInspectionType;
use App\Models\HsePpeCategory;
use App\Models\HsePpeItem;
use App\Models\HsePpeStockTransaction;
use App\Models\HseRiskLevel;

use App\Observers\HseAuditObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        HseInspectionType::observe(HseAuditObserver::class);
        HseChecklist::observe(HseAuditObserver::class);
        HseInspection::observe(HseAuditObserver::class);
        HseInspectionChecklistResult::observe(HseAuditObserver::class);

        HseFindingCategory::observe(HseAuditObserver::class);
        HseRiskLevel::observe(HseAuditObserver::class);
        HseFinding::observe(HseAuditObserver::class);
        HseCorrectiveAction::observe(HseAuditObserver::class);

        HsePpeCategory::observe(HseAuditObserver::class);
        HsePpeItem::observe(HseAuditObserver::class);
        HsePpeStockTransaction::observe(HseAuditObserver::class);
    }
}