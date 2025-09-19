<?php

namespace App\Services;

use App\Models\Due;
use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class AccountingService
{
    public function recordDuePayment(Due $due, string $paymentMethod, string $paymentDate, User $recorder): Transaction
    {
        // 1. Ana Transaction kaydını oluştur
        $transaction = $due->transaction()->create([
            'site_id' => $due->site_id,
            'transaction_date' => Carbon::parse($paymentDate),
            'description' => "{$due->period->format('F Y')} aidat ödemesi - Daire {$due->apartment->number}",
            'amount' => $due->amount,
            'type' => 'due_payment',
            'created_by_user_id' => $recorder->id,
        ]);

        // 2. Muhasebe hesaplarını bul
        // Bu kodlar normalde veritabanından dinamik olarak gelir.
        $receivablesAccount = FinancialAccount::where('code', '120.01')->firstOrFail(); // Sakinlerden Alacaklar
        $paymentAccount = ($paymentMethod === 'cash')
            ? FinancialAccount::where('code', '100.01')->firstOrFail() // Kasa Hesabı
            : FinancialAccount::where('code', '102.01')->firstOrFail(); // Banka Hesabı

        // 3. Yevmiye fişlerini (çift taraflı kayıt) oluştur
        // Kasa/Banka borçlanır (varlık artışı)
        $transaction->entries()->create([
            'financial_account_id' => $paymentAccount->id,
            'debit' => $due->amount,
            'credit' => 0,
        ]);

        // Alacaklar hesabı alacaklanır (varlık azalışı)
        $transaction->entries()->create([
            'financial_account_id' => $receivablesAccount->id,
            'debit' => 0,
            'credit' => $due->amount,
        ]);

        // 4. Aidatın durumunu güncelle
        $due->update(['status' => 'paid']);

        // Opsiyonel: Event fırlat (bildirim vb. için)
        // event(new DuePaid($due));

        return $transaction;
    }

    public function recordPayrollTransaction(Payroll $payroll): Transaction
    {
        // Gerekli muhasebe hesap kodları (Bunlar Hesap Planı'ndan çekilmelidir)
        $salaryExpenseAccount = '770.01'; // Personel Maaş Giderleri
        $sgkEmployerExpenseAccount = '770.02'; // SGK İşveren Giderleri
        $salariesPayableAccount = '335.01'; // Personele Borçlar
        $taxesPayableAccount = '360.01'; // Ödenecek Vergi ve Fonlar (Gelir V. + Damga V.)
        $sgkPayableAccount = '361.01'; // Ödenecek Sosyal Güvenlik Kesintileri (İşçi + İşveren)

        $description = "{$payroll->period_year}-{$payroll->period_month} Dönemi Maaş Bordrosu Tahakkuku";

        // 1. Ana Transaction kaydını oluştur
        $transaction = Transaction::create([
            'site_id' => $payroll->site_id,
            'transaction_date' => now()->endOfMonth(), // Genellikle ay sonunda tahakkuk yapılır
            'description' => $description,
            'amount' => $payroll->total_employer_cost,
            'type' => 'payroll_accrual',
            'transactionable_id' => $payroll->id,
            'transactionable_type' => Payroll::class,
            'created_by_user_id' => $payroll->created_by_user_id,
        ]);

        // 2. Yevmiye Fişleri (Çift Taraflı Kayıt)

        // Gider Hesapları BORÇLANDIRILIR
        // Brüt Maaş Gideri
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $salaryExpenseAccount)->first()->id,
            'debit' => $payroll->total_gross_salary,
        ]);

        // İşveren SGK Giderleri
        $sgkEmployerTotal = $payroll->items()->where('type', 'employer_cost')->sum('amount');
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $sgkEmployerExpenseAccount)->first()->id,
            'debit' => $sgkEmployerTotal,
        ]);

        // Pasif Hesaplar (Borçlar) ALACAKLANDIRILIR
        // Personele Ödenecek Net Maaş
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $salariesPayableAccount)->first()->id,
            'credit' => $payroll->total_net_salary,
        ]);

        // Devlete Ödenecek Vergiler
        $taxesTotal = $payroll->items()->whereIn('description', ['Gelir Vergisi', 'Damga Vergisi'])->sum('amount');
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $taxesPayableAccount)->first()->id,
            'credit' => $taxesTotal,
        ]);

        // SGK'ya Ödenecek Primler (İşçi + İşveren)
        $sgkTotal = $payroll->items()->whereIn('description', ['SGK Primi (%14)', 'İşsizlik Sig. (%1)', 'SGK İşveren Primi (%20.5)', 'İşsizlik Sig. İşveren (%2)'])->sum('amount');
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $sgkPayableAccount)->first()->id,
            'credit' => $sgkTotal,
        ]);

        return $transaction;
    }

    public function recordInvoiceAccrual(Invoice $invoice): Transaction
    {
        // Hesap kodlarını kategoriden veya ayarlardan dinamik al
        $expenseAccountCode = $invoice->category->financial_account_code;
        $vatAccountCode = '191.01'; // İndirilecek KDV (varsayılan)
        $vendorPayableAccountCode = '320.01'; // Tedarikçilere Borçlar (varsayılan)

        $transaction = $invoice->transaction()->create([ // morphOne ilişkisi varsayılıyor
            'site_id' => $invoice->site_id,
            'transaction_date' => $invoice->invoice_date,
            'description' => "Fatura Tahakkuku: {$invoice->description}",
            'amount' => $invoice->total_amount,
            'type' => 'invoice_accrual',
            'created_by_user_id' => $invoice->created_by_user_id,
        ]);

        // Gider Hesabı (Borç)
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $expenseAccountCode)->firstOrFail()->id,
            'debit' => $invoice->subtotal,
        ]);

        // KDV Hesabı (Borç)
        if ($invoice->tax_amount > 0) {
            $transaction->entries()->create([
                'financial_account_id' => FinancialAccount::where('code', $vatAccountCode)->firstOrFail()->id,
                'debit' => $invoice->tax_amount,
            ]);
        }

        // Tedarikçi Borç Hesabı (Alacak)
        $transaction->entries()->create([
            'financial_account_id' => FinancialAccount::where('code', $vendorPayableAccountCode)->firstOrFail()->id,
            'credit' => $invoice->total_amount,
        ]);

        return $transaction;
    }

}
