<?php

namespace App\Http\Controllers;

use App\Models\FeeTemplate;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Block;
use App\Models\Unit;

class FeeTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = FeeTemplate::with('applicable')->latest()->paginate(15);
        return view('fee-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fee-templates.create', [
            'sites' => Site::all(),
            'blocks' => Block::with('site')->get(),
            'units' => Unit::with('block.site')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'applicable_level' => 'required|in:site,block,unit',
            'site_id' => 'required_if:applicable_level,site|exists:sites,id',
            'block_id' => 'required_if:applicable_level,block|exists:blocks,id',
            'unit_id' => 'required_if:applicable_level,unit|exists:units,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ];

        if ($validated['applicable_level'] === 'site') {
            $data['applicable_type'] = Site::class;
            $data['applicable_id'] = $validated['site_id'];
        } elseif ($validated['applicable_level'] === 'block') {
            $data['applicable_type'] = Block::class;
            $data['applicable_id'] = $validated['block_id'];
        } elseif ($validated['applicable_level'] === 'unit') {
            $data['applicable_type'] = Unit::class;
            $data['applicable_id'] = $validated['unit_id'];
        }

        FeeTemplate::create($data);

        return redirect()->route('fee-templates.index')->with('success', 'Aidat şablonu başarıyla oluşturuldu.');

    }

    /**
     * Display the specified resource.
     */
    public function show(FeeTemplate $feeTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeTemplate $feeTemplate)
    {
        return view('fee-templates.edit', [
            'template' => $feeTemplate,
            'sites' => Site::all(),
            'blocks' => Block::with('site')->get(),
            'units' => Unit::with('block.site')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeTemplate $feeTemplate)
    {
        return redirect()->route('fee-templates.index')->with('success', 'Aidat şablonu başarıyla güncellendi.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeTemplate $feeTemplate)
    {
        $feeTemplate->delete();
        return redirect()->route('fee-templates.index')->with('success', 'Aidat şablonu başarıyla silindi.');

    }
}
