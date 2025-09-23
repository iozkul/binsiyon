<?php

use Illuminate\Support\Facades\Route;
use Modules\Personnel\Http\Controllers\EmployeeController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('personnel')
        ->name('personnel.')
        ->middleware('can:manage personnel')
        ->group(function () {

            Route::resource('employees', EmployeeController::class);
            Route::resource('payrolls', EmployeeController::class);
            // Bordro, puantaj gibi diğer rotalar da buraya eklenebilir.
            // Route::get('payrolls', ...)->name('payrolls.index');
        });

});
