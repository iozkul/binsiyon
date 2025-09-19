<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class SiteSelector extends Component
{
    public Collection $sites;
    public ?int $selected;

    /**
     * Create a new component instance.
     * @param int|null $selected Düzenleme formlarında önceden seçili gelen site ID'si.
     */
    public function __construct(?int $selected = null)
    {
        $this->selected = $selected;

        $user = Auth::user();
        if ($user->hasRole('super_admin')) {
            // Süper admin tüm siteleri görür.
            $this->sites = Site::all(['id', 'name']);
        } else {
            // Diğer yöneticiler sadece kendi sitelerini görür.
            $this->sites = Site::where('id', $user->site_id)->get(['id', 'name']);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.site-selector');
    }
}
