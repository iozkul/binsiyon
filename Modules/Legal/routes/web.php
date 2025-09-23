<?php

use Illuminate\Support\Facades\Route;
use Modules\Legal\Http\Controllers\LegalController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('legal')
        ->name('legal.')
        ->middleware('can:view legals')
        ->group(function () {

            Route::resource('debt-collection', LegalController::class);
            Route::resource('decision-ledger', LegalController::class);

            // Finansal Raporlar
            //Route::prefix('legal')->name('legal.')->group(function() {
                //Route::get('index', [LegalController::class, 'index'])->name('index');
                // Diğer finansal rapor rotaları (örn: 'details', 'cash-flow') buraya eklenebilir.
           // });
        });
});
