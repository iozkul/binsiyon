<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Module;
use Illuminate\Http\Request;

class SiteModuleController extends Controller
{
    /**
     * Belirtilen site için modül atama formunu gösterir.
     */
    public function edit(Site $site)
    {
        $this->authorize('manageModules', $site); // Policy kontrolü

        $modules = Module::all();
        $assignedModules = $site->modules()->pluck('id')->toArray();

        return view('admin.sites.modules', compact('site', 'modules', 'assignedModules'));
    }

    /**
     * Sitenin modüllerini günceller.
     */
    public function update(Request $request, Site $site)
    {
        $this->authorize('manageModules', $site);

        $request->validate([
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        $site->modules()->sync($request->input('modules', []));

        return redirect()->route('admin.sites.index')->with('success', $site->name . ' sitesinin modülleri güncellendi.');
    }
}
