<?php

use Illuminate\Support\Facades\Route;
use Modules\Residents\Http\Controllers\ResidentsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('residents', ResidentsController::class)->names('residents');
});
