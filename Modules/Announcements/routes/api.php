<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcements\Http\Controllers\AnnouncementsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('announcements', AnnouncementsController::class)->names('announcements');
});
