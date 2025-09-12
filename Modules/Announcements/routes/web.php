<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcements\Http\Controllers\AnnouncementsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('announcements', AnnouncementsController::class)->names('announcements');
});
