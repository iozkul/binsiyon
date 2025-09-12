<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        // Bu yetki kontrolünü eklemek iyi bir pratiktir.
        // Spatie seeder'ınızda 'manage finance' yetkisini oluşturmuştuk.
        $this->authorize('manage finance');

        // Gelecekte buraya aidat, borç gibi verileri çekecek kodlar gelecek.
        // Şimdilik sadece view'ı döndürelim.
        return view('finance.index');
    }
}
