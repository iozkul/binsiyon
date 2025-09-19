<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Finance\app\Models\MonthlyDue;
use Modules\Finance\app\Services\MonthlyDueService;
use Modules\Finance\app\Http\Requests\StoreMonthlyDueRequest;
use Modules\Finance\app\Http\Requests\UpdateMonthlyDueRequest;
use App\Models\Site;

class MonthlyDueController extends Controller
{
    protected $monthlyDueService;

    public function __construct(MonthlyDueService $monthlyDueService)
    {
        $this->monthlyDueService = $monthlyDueService;
        $this->authorizeResource(MonthlyDue::class, 'monthly_due');
    }

    public function index(Request $request)
    {
        $monthlyDues = $this->monthlyDueService->getFilteredDues($request->all());
        return view('finance::monthly_dues.index', compact('monthlyDues'));
    }

    public function create()
    {
        // TODO: Fetch sites and apartments based on user permissions
        $sites = Site::all();
        return view('finance::monthly_dues.create', compact('sites'));

    }

    public function store(StoreMonthlyDueRequest $request)
    {
        $this->monthlyDueService->create($request->validated());
        return redirect()->route('finance.monthly-dues.index')->with('success', 'Aidat başarıyla oluşturuldu.');
    }

    public function show(MonthlyDue $monthlyDue)
    {
        $monthlyDue->load(['site', 'block', 'resident', 'payments']);
        return view('finance::monthly_dues.show', compact('monthlyDue'));

    }

    public function edit(MonthlyDue $monthlyDue)
    {
        $sites = Site::all();
        return view('finance::monthly_dues.edit', compact('monthlyDue'));
    }

    public function update(UpdateMonthlyDueRequest $request, MonthlyDue $monthlyDue)
    {
        $this->monthlyDueService->update($monthlyDue, $request->validated());
        return redirect()->route('finance.monthly-dues.index')->with('success', 'Aidat başarıyla güncellendi.');
    }

    public function destroy(MonthlyDue $monthlyDue)
    {
        $monthlyDue->delete();
        return redirect()->route('finance.monthly-dues.index')->with('success', 'Aidat başarıyla silindi.');
    }
}
