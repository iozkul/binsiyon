<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Site;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Announcement::query();

        $announcements = Announcement::where('site_id', $user->unit?->block?->site_id)
            ->where(function ($query) use ($user) {
                // Kullanıcının rolüne hedeflenmiş duyuruları al
                $query->whereHas('targetRoles', function($q) use ($user) {
                    $q->whereIn('id', $user->roles->pluck('id'));
                })
                    // VEYA doğrudan kullanıcıya hedeflenmiş duyuruları al
                    ->orWhereHas('targetUsers', function($q) use ($user) {
                        $q->where('id', $user->id);
                    })
                    // VEYA hiç hedef seçilmemişse (herkese açık) duyuruları al
                    ->orWhere(function ($q) {
                        $q->doesntHave('targetRoles')->doesntHave('targetUsers');
                    });
            })
            ->latest()->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $this->authorize('create', Announcement::class);
        $user = Auth::user();

        $sites = $user->hasRole('super-admin') ? Site::all() : $user->managedSites;
        $roles = Role::all();
        // Performansı artırmak için sadece yöneticinin sitesindeki kullanıcıları çek
        $users = User::whereIn('site_id', $sites->pluck('id'))->get();

        return view('announcements.create', compact('sites', 'roles', 'users'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        $this->authorize('create', Announcement::class);
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['published_at'] = now();

        // Site yöneticisinin bu site için duyuru yayınlama yetkisi var mı?
        // Policy'de bu kontrol yapılmalı.
        $site = Site::findOrFail($validated['site_id']);
        $this->authorize('createForSite', [Announcement::class, $site]);

        Announcement::create($validated);

        // Buraya o sitedeki sakinlere bildirim gönderme kodu eklenebilir.

        return redirect()->route('announcements.index')->with('success', 'Duyuru başarıyla yayınlandı.');
*/
        $this->authorize('create', Announcement::class);

        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'exists:roles,id',
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
        ]);

        $site = Site::findOrFail($validated['site_id']);
        $this->authorize('createForSite', [Announcement::class, $site]);

        $announcement = Announcement::create([
            'site_id' => $validated['site_id'],
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'published_at' => now(),
        ]);

        if (!empty($validated['target_roles'])) {
            $announcement->targetRoles()->attach($validated['target_roles']);
        }
        if (!empty($validated['target_users'])) {
            $announcement->targetUsers()->attach($validated['target_users']);
        }

        // Buraya o sitedeki hedeflenen kullanıcılara bildirim gönderme kodu eklenebilir.

        return redirect()->route('announcements.index')->with('success', 'Duyuru başarıyla yayınlandı.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        // Kullanıcı duyuruyu gördüğü anda okundu olarak işaretle
        $announcement->reads()->syncWithoutDetaching(auth()->id());

        return view('announcements.show', compact('announcement'));
    }
    public function readStatus(Announcement $announcement)
    {
        $this->authorize('viewReadStatus', $announcement); // Policy ile yetki kontrolü

        // Duyuruyu okuyan kullanıcıların ID'lerini al
        $readUserIds = $announcement->reads()->pluck('users.id');

        // Okuyanları ve okumayanları ayır
        $readUsers = User::whereIn('id', $readUserIds)->get();
        $unreadUsers = User::whereNotIn('id', $readUserIds)->get(); // Gerekirse belirli rollere göre filtrele

        return view('admin.announcements.read-status', compact('announcement', 'readUsers', 'unreadUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
