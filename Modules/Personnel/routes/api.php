<?php

use Illuminate\Support\Facades\Route;
use Modules\Personnel\Http\Controllers\PersonnelController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('personnels', PersonnelController::class)->names('personnel');
});
