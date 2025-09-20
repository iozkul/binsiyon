<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Site;
use App\Services\FeeGenerationService;

class GenerateMonthlyFeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(FeeGenerationService $feeService): void
    {
        // Otomatik tahakkuk ayarı olan siteleri al
        $sites = Site::all(); // Veya Site modeline eklenecek bir 'auto_fee_generation' scope'u ile

        foreach ($sites as $site) {
            $feeService->generateForSite($site);
        }
    }
}
