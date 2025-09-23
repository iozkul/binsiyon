<?php

use Illuminate\Support\Facades\Route;
//use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\App\Http\Controllers\MonthlyDueController;
use Modules\Finance\App\Http\Controllers\PaymentController;
use Modules\Finance\App\Http\Controllers\InvoiceController;
use Modules\Finance\App\Http\Controllers\FinanceController;
use Modules\Finance\App\Http\Controllers\ExpenseController;
use Modules\Finance\App\Http\Controllers\BudgetController;
use Modules\Finance\App\Http\Controllers\IncomeController;
use Modules\Finance\App\Http\Controllers\VendorController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/sites-switch', [FinanceController::class, 'switch'])
        ->name('sites.switch');

});


Route::middleware(['auth', 'verified', 'site.selected'])->group(function () {

    Route::prefix('finance')
        ->name('finance.')
        ->group(function () {

            // Planlama (Bütçeler)
            // Yetki: manage budgets
            //Route::resource('budgets', BudgetController::class)->middleware('can:manage budgets');
            Route::resource('budgets', BudgetController::class); // middleware kapalı


            // Gelir Yönetimi
            // Yetki: view incomes
            Route::resource('incomes', IncomeController::class)->middleware('can:view incomes');

            // Gider Yönetimi
            // Yetki: create expenses
            Route::resource('expenses', ExpenseController::class)->middleware('can:create expenses');

            // Aidat & Borç Yönetimi
            // Yetki: generate dues
            Route::resource('monthly-dues', MonthlyDueController::class)->middleware('can:generate dues');
            //Route::resource('debts', DebtController::class)->middleware('can:generate dues');

            // Tahsilatlar
            // Yetki: manage payments
            Route::resource('payments', PaymentController::class)->middleware('can:manage payments');

            // Varlıklar -> Tedarikçiler
            // Yetki: manage vendors
            Route::resource('vendors', VendorController::class)->middleware('can:manage vendors');
        });

});

/*

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    // Yeni Bütçe Route'ları
    // Route'ları isimlendirirken 'finance.' ön ekini veriyoruz.
    // Böylece route('finance.budgets.index') gibi isimler geçerli oluyor.
    Route::name('finance.')->prefix('finance')->group(function () {
        Route::resource('budgets', BudgetController::class);
    });

});
Route::middleware(['auth', 'role:resident|property_owner'])
    ->prefix('my-finances')
    ->name('my-finances.')
    ->group(function () {
        Route::get('/', [MyFinancesController::class, 'index'])->name('index');
    });


Route::middleware(['auth', 'role:super-admin|site-admin|accountant', 'active_site'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('finance.monthly-dues.index');
        })->name('index');
        Route::get('monthly-dues', [FinanceController::class, 'monthlyDues'])->name('monthly-dues.index');

       // Route::resource('monthly-dues', MonthlyDueController::class);




        Route::prefix('finance')->name('finance.')->middleware(['auth'])->group(function () {

            // Finans Yönetimi
            Route::middleware(['can:manage finance'])->group(function () {
                Route::resource('fees', FeeController::class);
                Route::resource('debts', DebtController::class);
                Route::resource('payments', PaymentController::class);
                Route::resource('fee-templates', FeeTemplateController::class);
                Route::resource('expenses', ExpenseController::class);
                Route::resource('incomes', IncomeController::class);
                Route::resource('vendors', VendorController::class);
                // Route::resource('finance', FinanceController::class)->names('finance.');
                Route::resource('monthly-dues', MonthlyDueController::class);

                ;
            });
        });

        Route::prefix('monthly-dues')->name('finance.monthly-dues.')->group(function () {
            Route::get('/', [MonthlyDueController::class, 'index'])->name('index');
            // Diğer CRUD operasyonları için rotalar (create, store, edit, update, destroy) buraya eklenebilir.
        });
        //Route::middleware(['auth', 'verified'])->group(function () {
            /* Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');*/
            // Önceki cevapta oluşturduğumuz gecikme faizi hesaplama rotası da burada olmalı.
/*
            Route::post('/finance/calculate-late-fees', [FinanceController::class, 'calculateLateFees'])
                ->name('finance.calculate-late-fees')
                ->middleware('can:manage finance');
        });

        Route::post('expenses/{expense}/cancel', [ExpenseController::class, 'cancel'])->name('expenses.cancel');



    });


*/
