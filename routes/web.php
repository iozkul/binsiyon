<?php

use App\Http\Controllers\Auth\ConfirmationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OwnerDashboardController;

use App\Http\Controllers\FeeController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
//use App\Http\Controllers\OnlinePaymentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\FeeTemplateController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReservationController;
//use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PackageController;
//use App\Http\Controllers\Admin\FixtureController as AdminFixtureController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Modules\Finance\app\Http\Controllers\MonthlyDueController;
use App\Http\Controllers\Admin\SiteModuleController;

/*
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/assign-package', [UserController::class, 'assignPackageForm'])->name('users.assign-package.form');
    Route::post('/users/{user}/assign-package', [UserController::class, 'assignPackage'])->name('users.assign-package');
    //Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    //Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
});
*/

use App\Http\Controllers\Admin\ModuleController;

Route::get('/', function () {    return view('welcome'); });

//Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
//Route::post('/modules/{module}/toggle', [ModuleController::class, 'toggle'])->name('modules.toggle');
/*
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Hepsi 'AdminUserController' olarak düzeltildi
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/assign-package', [AdminUserController::class, 'assignPackageForm'])
        ->name('users.assign-package.form');
    Route::post('/users/{user}/assign-package', [AdminUserController::class, 'assignPackage'])
        ->name('users.assign-package');
    Route::post('/users/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])
        ->name('users.toggle-ban');
    Route::resource('packages', PackageController::class);
    Route::resource('roles', RoleController::class);
});
*/
//Route::resource('/packages', PackageController::class);



/*Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');
*/
Route::get('/confirm-account/{token}', [ConfirmationController::class, 'confirm'])
    ->name('user.confirm');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // SADECE SÜPER-ADMİN'İN ERİŞEBİLECEĞİ ALANLAR
    Route::middleware(['role:super-admin'])->prefix('admin')->name('admin.')->group(function ()
    {
            // Örn: Yeni profesyonel yönetim firması tanımlama, genel sistem ayarları vb.
            Route::get('/settings', [AdminUserController::class, 'settings'])
            ->name('settings');
            // Kullanıcı yönetimi rotasını da buraya alabiliriz.
            Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');
            Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
            Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
            Route::delete('/sites/{site}', [SiteController::class, 'destroy'])
            ->name('sites.destroy')
            ->middleware(['can:manage sites']);
            Route::get('/users/{user}/assign-package', [AdminUserController::class, 'assignPackageForm'])
            ->name('users.assign-package.form');
            Route::post('/users/{user}/assign-package', [AdminUserController::class, 'assignPackage'])
            ->name('users.assign-package');
            Route::resource('packages', PackageController::class);
            Route::resource('roles', RoleController::class);
            Route::post('/users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
        Route::post('users/{user}/roles', [\App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assignRoles');
        Route::get('users/{user}/roles', [\App\Http\Controllers\Admin\UserController::class, 'manageRoles'])->name('users.manageRoles');
        Route::get('activity-logs', [\App\Http\Controllers\Admin\UserActivityLogController::class, 'index'])->name('logs.index');
        Route::get('sites/{site}/modules', [SiteModuleController::class, 'edit'])->name('sites.modules.edit');
        Route::post('sites/{site}/modules', [SiteModuleController::class, 'update'])->name('sites.modules.update');

    });
    // SİTE-ADMİN ve SÜPER-ADMİN'İN ERİŞEBİLECEĞİ ALANLAR
    Route::middleware(['role:super-admin|site-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('sites', SiteController::class);
        Route::resource('blocks', BlockController::class);
        Route::get('/sites', [SiteController::class, 'index'])->name('sites.index')
            ->middleware(['can:manage sites']); // <-- Sadece 'site-list' izni olanlar girebilir



    });
    Route::resource('fixtures', FixtureController::class);
    Route::resource('reservations', ReservationController::class)->middleware('permission:manage sites');
    Route::prefix('finance')->name('finance.')->middleware(['auth'])->group(function () {
        Route::resource('monthly-dues', MonthlyDueController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/units/{unit}/convert', [UnitController::class, 'convert'])->name('units.convert');
    Route::get('/users/manage-roles', [UserController::class, 'index'])->name('users.manage_roles');
    /*
    Route::resource('sites', SiteController::class);
    Route::resource('blocks', BlockController::class);
    Route::resource('apartments', ApartmentController::class);
    //Route::resource('residents', ResidentController::class);
    Route::resource('announcements', AnnouncementController::class);
*/
    // Kullanıcı ve Rol Yönetimi (Sadece 'manage users' izni olanlar)
    Route::middleware(['can:manage users'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::get('/residents/assign-roles', [ResidentController::class, 'showAssignForm'])->name('residents.assign_roles_form');
        Route::post('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update_role');
        Route::resource('residents', ResidentController::class)->names('residents');


    });

    // Site, Blok, Daire ve Sakin Yönetimi
    Route::middleware(['can:manage sites'])->group(function () {
        Route::resource('sites', SiteController::class);
        Route::resource('blocks', BlockController::class);
        Route::resource('units', UnitController::class);
        //Route::resource('residents', ResidentController::class);
    });

    // Finans Yönetimi
    Route::middleware(['can:manage finance'])->group(function () {
        Route::resource('fees', FeeController::class);
        Route::resource('debts', DebtController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('fee-templates', FeeTemplateController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('incomes', IncomeController::class);
    });

    Route::middleware(['can:manage residents'])->group(function () {
        Route::resource('residents', ResidentController::class);
    });

    // Duyuru Yönetimi
    Route::middleware(['can:manage announcements'])->group(function () {
        Route::resource('announcements', AnnouncementController::class);
    });

    // Rapor Görüntüleme
    Route::middleware(['can:view reports'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/income-expense', [ReportController::class,'incomeExpense'])->name('income-expense');
        Route::get('/debtors', [ReportController::class,'debtors'])->name('debtors');
    });

    // Diğer tekil rotalar...
    Route::resource('fixtures', FixtureController::class); // Bu rotanın iznini belirleyin
    Route::resource('messages', MessageController::class); // Bu rotanın iznini belirleyin



    /*
    Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');


    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    // 1- Kullanıcı & Rol
    Route::resource('users', UserController::class)->middleware('permission:manage users');
    Route::resource('roles', RoleController::class)->middleware('can:manage users');
    Route::resource('permissions', PermissionController::class)->middleware('can:manage users');Route::get('/residents/assign-roles', [ResidentController::class, 'showAssignForm'])->name('residents.assign_roles_form');
//Route::post('/residents/{user}/assign-role', [ResidentController::class, 'assignRole'])->name('residents.assign_role');
//Route::get('/residents', [App\Http\Controllers\ResidentController::class, 'index'])->name('residents.index');
    Route::resource('residents', ResidentController::class)->middleware('permission:manage sites');
    Route::resource('users', UserController::class);//->middleware('permission:manage users');
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->middleware(['auth', 'role:property-owner']) // Sadece mülk sahipleri erişebilsin
        ->name('owner.dashboard');
    Route::get('/users/{user}/ledger', [UserController::class, 'showLedger'])->name('users.ledger');

// Bu rota, tüm kullanıcıları listeleyecek ve rol yönetimi sağlayacak.
    Route::get('/users/manage-roles', [UserController::class, 'index'])->name('users.manage_roles');
    Route::post('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update_role');

    // 2- Bina / Blok / Daire / Sakin
    Route::resource('sites', SiteController::class)->middleware('permission:manage sites');
    Route::resource('blocks', BlockController::class)->middleware('permission:manage sites');
    //Route::resource('apartments', ApartmentController::class)->middleware('permission:manage sites');
    Route::resource('units', UnitController::class)->middleware('permission:manage sites');
    Route::resource('residents', ResidentController::class)->middleware('permission:manage sites');
    Route::resource('reservations', ReservationController::class)->middleware('permission:manage sites');

    // 3- Aidat / Borç / Ödeme
    Route::resource('fees', FeeController::class)->middleware('permission:manage finance');
    Route::resource('debts', DebtController::class)->middleware('permission:manage finance');
    Route::resource('payments', PaymentController::class)->middleware('permission:manage finance');
    Route::resource('fee-templates', FeeTemplateController::class)
        ->middleware('can:manage finance'); // Sadece finans yöneticileri erişebilsin

    // 4- Gelir / Gider
    Route::resource('expenses', ExpenseController::class)->middleware('permission:manage finance');
    Route::resource('incomes', IncomeController::class)->middleware('permission:manage finance');

    // 5- Online Ödeme
    //Route::resource('online-payments', OnlinePaymentController::class)->middleware('permission:manage payments');

    // 6- Bildirim & Duyuru
    Route::resource('announcements', AnnouncementController::class)->middleware('permission:manage announcements');
    //Route::resource('documents', DocumentController::class)->middleware('permission:manage announcements');

    // 7- Raporlama
    Route::get('reports/income-expense', [ReportController::class,'incomeExpense'])->middleware('permission:view reports')->name('reports.income-expense');
    Route::get('reports/debtors', [ReportController::class,'debtors'])->middleware('permission:view reports')->name('reports.debtors');
    Route::get('reports/payment-stats', [ReportController::class,'paymentStats'])->middleware('permission:view reports')->name('reports.payment-stats');
*/
    // routes/web.php (geçici debug)
    if (app()->environment('local')) {
        \DB::listen(function($query) {
            \Log::info('SQL', ['sql' => $query->sql, 'bindings' => $query->bindings, 'time' => $query->time]);
        });
    }

});

require __DIR__.'/auth.php';
