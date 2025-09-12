<?php
namespace App\Http\Controllers;

use App\Models\ReservableResource;
use Illuminate\Http\Request;

class ReservableResourceController extends Controller
{
    public function index()
    {
        // Yönetici sadece kendi sitesindeki kaynakları görmeli
        $resources = ReservableResource::where('site_id', auth()->user()->site_id)->get();
        return view('reservations.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('reservations.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rules' => 'nullable|string',
            'requires_approval' => 'required|boolean',
        ]);

        auth()->user()->site->reservableResources()->create($validated);

        return redirect()->route('reservable-resources.index')->with('success', 'Kaynak başarıyla oluşturuldu.');
    }

    // edit, update, destroy metodları standart CRUD yapısında eklenebilir.
}
