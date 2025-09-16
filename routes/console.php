<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('fixtures:check-maintenance')
    ->dailyAt('09:00') // Her gün sabah 9'da çalıştır.
    ->timezone('Europe/Istanbul'); // Saat dilimini Türkiye olarak ayarla.
// Her ayın 1'inde, saat 01:00'de 'app:generate-monthly-dues' komutunu çalıştırır.
Schedule::command('app:generate-monthly-dues')->monthlyOn(1, '01:00');
