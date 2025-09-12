<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('fixtures:check-maintenance')
    ->dailyAt('09:00') // Her gün sabah 9'da çalıştır.
    ->timezone('Europe/Istanbul'); // Saat dilimini Türkiye olarak ayarla.
