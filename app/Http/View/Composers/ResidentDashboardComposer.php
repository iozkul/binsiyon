<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Finance\app\Services\DashboardService;

class ResidentDashboardComposer
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function compose(View $view): void
    {
        $data = $this->dashboardService->getResidentData(Auth::user());
        $view->with($data);
    }
}
