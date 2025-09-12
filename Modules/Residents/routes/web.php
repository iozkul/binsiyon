<?php

use Illuminate\Support\Facades\Route;
use Modules\Residents\Http\Controllers\ResidentsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('residents', ResidentsController::class)->names('residents');
});
