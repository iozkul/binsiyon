<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;


class ModuleController extends Controller
{
    public function index()
    {
        $diskModules = $this->getModulesFromDisk();
        $dbModules = Module::all()->keyBy('name');

        // Disk'teki modüllerle DB'dekileri birleştirerek son durumu hazırla
        $modules = collect($diskModules)->map(function ($diskModule) use ($dbModules) {
            $dbModule = $dbModules->get($diskModule['name']);
            return [
                'name' => $diskModule['name'],
                'description' => $diskModule['description'],
                'is_active' => $dbModule ? $dbModule->is_active : false,
                'is_registered' => (bool)$dbModule,
            ];
        });

        return view('admin.modules.index', compact('modules'));
    }

    public function toggle(string $moduleName)
    {
        $diskModule = collect($this->getModulesFromDisk())->firstWhere('name', $moduleName);
        if (!$diskModule) {
            return back()->with('error', 'Modül dosyaları bulunamadı.');
        }

        $module = Module::firstOrCreate(
            ['name' => $moduleName],
            ['description' => $diskModule['description'], 'is_active' => false]
        );

        $module->update(['is_active' => !$module->is_active]);

        // Aktif modüllerin ServiceProvider listesini yeniden oluştur
        $this->updateModuleProviders();

        // Cache temizliği önemli!
        Artisan::call('cache:clear');

        $status = $module->is_active ? 'aktive edildi' : 'deaktive edildi';
        return back()->with('success', "$moduleName modülü başarıyla $status.");
    }

    // Disk'ten modülleri okur
    private function getModulesFromDisk(): array
    {
        $modules = [];
        $modulePaths = File::directories(base_path('Modules'));

        foreach ($modulePaths as $path) {
            $jsonPath = $path . '/module.json';
            if (File::exists($jsonPath)) {
                $config = json_decode(File::get($jsonPath), true);
                if (isset($config['name']) && isset($config['provider'])) {
                    $modules[] = $config;
                }
            }
        }
        return $modules;
    }

    // Aktif modüller için provider listesini günceller
    private function updateModuleProviders()
    {
        $activeProviders = Module::where('is_active', true)
            ->get()
            ->map(function ($module) {
                // module.json'dan provider yolunu oku
                $config = json_decode(File::get(base_path('Modules/' . $module->name . '/module.json')), true);
                // Class referansını string olarak ekle
                return "        " . $config['provider'] . "::class,";
            })->implode("\n");

        $content = "<?php\n\nreturn [\n" . $activeProviders . "\n];\n";
        File::put(base_path('bootstrap/module_providers.php'), $content);
    }
}
