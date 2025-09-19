<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Site;

class TransactionService
{
    /**
     * Sisteme bir gelir işlemi kaydeder.
     * @return Transaction
     */
    public function recordIncome(Site $site, float $amount, string $description, string $date): Transaction
    {
        return Transaction::create([
            'site_id' => $site->id,
            'amount' => $amount,
            'type' => 'income',
            'description' => $description,
            'transaction_date' => $date,
        ]);
    }

    /**
     * Sisteme bir gider işlemi kaydeder.
     * @return Transaction
     */
    public function recordExpense(Site $site, float $amount, string $description, string $category, string $date): Transaction
    {
        return Transaction::create([
            'site_id' => $site->id,
            'amount' => -$amount, // Giderler negatif değer olarak kaydedilir
            'type' => 'expense',
            'description' => $description,
            'meta' => ['category' => $category], // Ekstra bilgileri JSON olarak sakla
            'transaction_date' => $date,
        ]);
    }
}
