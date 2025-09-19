<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\Site;
use App\Models\StaffMember;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    // Not: Bu oranlar normalde bir 'settings' tablosundan veya config dosyasından gelmelidir.
    const SGK_EMPLOYEE_RATE = 0.14;
    const UNEMPLOYMENT_EMPLOYEE_RATE = 0.01;
    const SGK_EMPLOYER_RATE = 0.205;
    const UNEMPLOYMENT_EMPLOYER_RATE = 0.02;
    const STAMP_TAX_RATE = 0.00759;

    public function __construct(protected AccountingService $accountingService) {}

    public function generatePayroll(Site $site, int $year, int $month, int $userId): Payroll
    {
        // Örnek gelir vergisi dilimleri (2025 için varsayımsal)
        $incomeTaxBrackets = [
            ['limit' => 70000, 'rate' => 0.15],
            ['limit' => 150000, 'rate' => 0.20],
            ['limit' => 550000, 'rate' => 0.27],
            // ... diğer dilimler
        ];

        return DB::transaction(function () use ($site, $year, $month, $userId, $incomeTaxBrackets) {
            $staffMembers = $site->staffMembers()->where('is_active', true)->get();

            $payroll = Payroll::create([
                'site_id' => $site->id, 'period_year' => $year, 'period_month' => $month,
                'total_gross_salary' => 0, 'total_net_salary' => 0, 'total_sgk_cost' => 0,
                'total_employer_cost' => 0, 'status' => 'draft', 'created_by_user_id' => $userId,
            ]);

            $totalGross = 0; $totalNet = 0; $totalSgk = 0; $totalEmployerCost = 0;

            foreach ($staffMembers as $staff) {
                // Burada her personel için brütten nete hesaplama yapılır.
                // Bu örnekte basitleştirilmiş bir hesaplama mevcuttur.
                $grossSalary = $staff->salary_amount;
                $sgkEmployee = $grossSalary * self::SGK_EMPLOYEE_RATE;
                $unemploymentEmployee = $grossSalary * self::UNEMPLOYMENT_EMPLOYEE_RATE;

                $incomeTaxBase = $grossSalary - $sgkEmployee - $unemploymentEmployee;

                // TODO: Kümülatif vergi matrahına göre doğru vergi dilimini ve oranını hesapla.
                // Bu örnek için sabit %15 alıyoruz.
                $incomeTax = $incomeTaxBase * 0.15;
                $stampTax = $grossSalary * self::STAMP_TAX_RATE;

                $totalDeductions = $sgkEmployee + $unemploymentEmployee + $incomeTax + $stampTax;
                $netSalary = $grossSalary - $totalDeductions;

                $sgkEmployer = $grossSalary * self::SGK_EMPLOYER_RATE;
                $unemploymentEmployer = $grossSalary * self::UNEMPLOYMENT_EMPLOYER_RATE;

                $staffEmployerCost = $grossSalary + $sgkEmployer + $unemploymentEmployer;

                // Bordro kalemlerini kaydet
                $payroll->items()->createMany([
                    ['staff_member_id' => $staff->id, 'description' => 'Brüt Maaş', 'type' => 'earning', 'amount' => $grossSalary],
                    ['staff_member_id' => $staff->id, 'description' => 'SGK Primi (%14)', 'type' => 'deduction', 'amount' => $sgkEmployee],
                    ['staff_member_id' => $staff->id, 'description' => 'İşsizlik Sig. (%1)', 'type' => 'deduction', 'amount' => $unemploymentEmployee],
                    ['staff_member_id' => $staff->id, 'description' => 'Gelir Vergisi', 'type' => 'deduction', 'amount' => $incomeTax],
                    ['staff_member_id' => $staff->id, 'description' => 'Damga Vergisi', 'type' => 'deduction', 'amount' => $stampTax],
                    ['staff_member_id' => $staff->id, 'description' => 'Net Maaş', 'type' => 'info', 'amount' => $netSalary],
                    ['staff_member_id' => $staff->id, 'description' => 'SGK İşveren Primi (%20.5)', 'type' => 'employer_cost', 'amount' => $sgkEmployer],
                    ['staff_member_id' => $staff->id, 'description' => 'İşsizlik Sig. İşveren (%2)', 'type' => 'employer_cost', 'amount' => $unemploymentEmployer],
                ]);

                $totalGross += $grossSalary;
                $totalNet += $netSalary;
                $totalSgk += ($sgkEmployee + $sgkEmployer);
                $totalEmployerCost += $staffEmployerCost;
            }

            $payroll->update([
                'total_gross_salary' => $totalGross, 'total_net_salary' => $totalNet,
                'total_sgk_cost' => $totalSgk, 'total_employer_cost' => $totalEmployerCost,
                'status' => 'finalized',
            ]);

            // Muhasebe fişini oluştur
            $this->accountingService->recordPayrollTransaction($payroll);

            return $payroll;
        });
    }
}
