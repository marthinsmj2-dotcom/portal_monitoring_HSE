<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\HseInspectionTypeController;
use App\Http\Controllers\Api\HseChecklistController;
use App\Http\Controllers\Api\HseInspectionController;
use App\Http\Controllers\Api\HseFindingController;
use App\Http\Controllers\Api\HseFindingCategoryController;
use App\Http\Controllers\Api\HseRiskLevelController;
use App\Http\Controllers\Api\HseCorrectiveActionController;
use App\Http\Controllers\Api\HsePpeCategoryController;
use App\Http\Controllers\Api\HsePpeItemController;
use App\Http\Controllers\Api\HsePpeStockTransactionController;
use App\Http\Controllers\Api\HsePpeStockController;
use App\Http\Controllers\Api\HseReportController;

Route::apiResource(
    'hse/inspection-types',
    HseInspectionTypeController::class
);

Route::apiResource(
    'hse/checklists',
    HseChecklistController::class
);

Route::apiResource(
    'hse/inspections',
    HseInspectionController::class
);

Route::apiResource(
    'hse/findings',
    HseFindingController::class
);

Route::apiResource(
    'hse/finding-categories',
    HseFindingCategoryController::class
);

Route::apiResource(
    'hse/risk-levels',
    HseRiskLevelController::class
);

Route::apiResource(
    'hse/corrective-actions',
    HseCorrectiveActionController::class
);

Route::patch(
    'hse/findings/{hseFinding}/verify',
    [HseFindingController::class, 'verify']
);

Route::apiResource(
    'hse/ppe-categories',
    HsePpeCategoryController::class
);

Route::apiResource(
    'hse/ppe-items',
    HsePpeItemController::class
);

Route::apiResource(
    'hse/ppe-stock-transactions',
    HsePpeStockTransactionController::class
);

Route::get(
    'hse/ppe-stock',
    [HsePpeStockController::class, 'index']
);

Route::get(
    'hse/ppe-stock/{hsePpeItem}',
    [HsePpeStockController::class, 'show']
);

Route::get(
    'hse/reports',
    [HseReportController::class, 'index']
);