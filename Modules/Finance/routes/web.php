<?php

use Illuminate\Support\Facades\Route;
//use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\app\Http\Controllers\MonthlyDueController;
use Modules\Finance\app\Http\Controllers\PaymentController;
use Modules\Finance\app\Http\Controllers\InvoiceController;
use Modules\Finance\app\Http\Controllers\MyFinancesController;
use Modules\Finance\app\Http\Controllers\FinanceController;

use Modules\Finance\app\Http\Controllers\IncomeController;
use Modules\Finance\app\Http\Controllers\ExpenseController;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('finances', FinanceController::class)->names('finance');
});
Route::middleware(['auth', 'role:resident|property_owner'])
    ->prefix('my-finances')
    ->name('my-finances.')
    ->group(function () {
        Route::get('/', [MyFinancesController::class, 'index'])->name('index');
    });


Route::middleware(['auth', 'role:super-admin|site-admin|accountant'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('finance.monthly-dues.index');
        })->name('index');
        Route::get('monthly-dues', [FinanceController::class, 'monthlyDues'])->name('monthly-dues.index');

       // Route::resource('monthly-dues', MonthlyDueController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('invoices', InvoiceController::class);

        Route::prefix('finance')->name('finance.')->middleware(['auth'])->group(function () {
            Route::resource('monthly-dues', MonthlyDueController::class);
        });

        Route::prefix('monthly-dues')->name('finance.monthly-dues.')->group(function () {
            Route::get('/', [MonthlyDueController::class, 'index'])->name('index');
            // Diğer CRUD operasyonları için rotalar (create, store, edit, update, destroy) buraya eklenebilir.
        });

        Route::middleware('role:super_admin|site_owner|accountant')->group(function () {
            Route::resource('monthly-dues', MonthlyDueController::class);
            Route::resource('incomes', IncomeController::class);
            Route::resource('expenses', ExpenseController::class);
            Route::resource('payments', PaymentController::class);
        });


    });

// Sakin/Mülk Sahibi Rotaları
Route::middleware(['auth', 'role:resident|property_owner'])
    ->prefix('my-finances')
    ->name('my-finances.')
    ->group(function () {
        Route::get('/', [MyFinancesController::class, 'index'])->name('index');
    });
