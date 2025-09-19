<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Budget;
use App\Models\Unit;
use App\Models\Fee;
use Carbon\Carbon;

class GenerateMonthlyDues extends Command
{
    protected $signature = 'app:generate-monthly-dues';
    protected $description = 'Generates monthly dues for all units based on the approved budget.';

    public function handle()
    {
        $this->info('Starting monthly due generation...');

        $today = Carbon::today();
        $approvedBudgets = Budget::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->get();

        foreach ($approvedBudgets as $budget) {
            $totalExpense = $budget->items()->where('type', 'expense')->sum('estimated_amount');
            $totalIncome = $budget->items()->where('type', 'income')->sum('estimated_amount');
            $netExpense = $totalExpense - $totalIncome;

            $units = Unit::whereHas('block', function($query) use ($budget) {
                $query->where('site_id', $budget->site_id);
            })->get();

            if ($units->isEmpty() || $netExpense <= 0) {
                continue;
            }

            // Dağıtım arsa payına veya eşit olarak yapılabilir. Şimdilik eşit yapalım.
            $duePerUnit = $netExpense / 12 / $units->count();

            foreach ($units as $unit) {
                // Ay için daha önce borç oluşturulmuş mu kontrol et
                $existingFee = Fee::where('unit_id', $unit->id)
                    ->where('description', 'LIKE', $today->format('Y-m').'% Aidat Borcu')
                    ->exists();

                if (!$existingFee) {
                    Fee::create([
                        'site_id' => $budget->site_id,
                        'unit_id' => $unit->id,
                        'user_id' => $unit->owner_id, // Borç mülk sahibine yazılır
                        'amount' => round($duePerUnit, 2),
                        'due_date' => $today->endOfMonth(),
                        'description' => $today->format('Y-m') . ' Aidat Borcu',
                        'type' => 'due'
                    ]);
                    $this->line("Due created for Unit #{$unit->id} in Site #{$budget->site_id}");
                }
            }
        }
        $this->info('Monthly due generation completed.');
    }
}
