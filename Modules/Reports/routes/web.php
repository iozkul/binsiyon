<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\FinancialReportController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('reports')
        ->name('reports.')
        ->middleware('can:view reports')
        ->group(function () {

            // Finansal Raporlar
            Route::prefix('financial')->name('financial.')->group(function() {
                Route::get('summary', [FinancialReportController::class, 'summary'])->name('summary');
                // Diğer finansal rapor rotaları (örn: 'details', 'cash-flow') buraya eklenebilir.
            });
        });

});
