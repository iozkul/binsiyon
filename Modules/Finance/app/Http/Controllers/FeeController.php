<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FeeTemplate;

class FeeController extends Controller
{
    public function index()
    {
        $this->authorize('manage finance');

        $user = Auth::user();

        // Temel sorguyu başlatıyoruz. İlişkili verileri tek seferde çekmek için 'with' kullanıyoruz.
        $query = Fee::with(['user', 'apartment']);

        // Kullanıcının rolüne göre sorguyu şekillendiriyoruz.
        if ($user->hasRole('super-admin')) {
            // Super admin hiçbir kısıtlama olmadan her şeyi görür.
            // Bu yüzden sorguya ek bir koşul eklemiyoruz.
        }
        elseif ($user->hasRole('site-admin')) {
            // Site yöneticisi sadece kendi yönettiği sitedeki aidatları görür.
            // User modelinde 'manages_site_id' adında bir sütun olduğunu varsayıyoruz.
            $query->where('site_id', $user->manages_site_id);
        }
        elseif ($user->hasRole('block-admin')) {
            // Blok yöneticisi sadece kendi yönettiği bloktaki aidatları görür.
            // User modelinde 'manages_block_id' adında bir sütun olduğunu varsayıyoruz.
            $query->where('block_id', $user->manages_block_id);
        }
        else {
            // Diğer tüm kullanıcılar (örneğin 'resident') sadece kendi aidatlarını görür.
            $query->where('user_id', $user->id);
        }

        // Sorguyu tamamla ve verileri son ödeme tarihine göre sırala.
        $fees = $query->latest('due_date')->paginate(20);

        return view('fees.index', compact('fees'));
    }
    /**
     * Toplu aidat oluşturma formunu gösterir.
     */
    public function create()
    {
        $this->authorize('manage finance');

        // Formda site seçimi yapabilmek için tüm siteleri view'a gönderiyoruz.
        $sites = Site::all();

        return view('fees.create', compact('sites'));
    }

    /**
     * Formdan gelen bilgilere göre toplu aidat (borç) oluşturur.
     */
    public function store(Request $request)
    {
        $this->authorize('manage finance');

        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);
        //$residents = User::where('site_id', $validated['site_id'])->get();
        $residents = User::whereNotNull('unit_id') // `apartment_id` yerine `unit_id` kullanmalısınız
        ->with('unit.block.site') // Gerekli ilişkileri tek sorguda çek
        ->get();
        $feesCreatedCount = 0;

        if ($residents->isEmpty()) {
            return back()->with('error', 'Seçilen sitede kayıtlı sakin bulunamadı.');
        }
        /*
        foreach ($residents as $resident) {
            // Sakinin bir dairesi olduğundan emin olalım
            if ($resident->apartment_id) {
                Fee::create([
                    'site_id' => $validated['site_id'],
                    'block_id' => $resident->block_id, // User modelinde bu bilgi olmalı
                    'apartment_id' => $resident->apartment_id, // User modelinde bu bilgi olmalı
                    'user_id' => $resident->id,
                    'description' => $validated['description'],
                    'amount' => $validated['amount'],
                    'due_date' => $validated['due_date'],
                    'paid_at' => null, // Henüz ödenmedi
                ]);
            }
        }

        return redirect()->route('fees.index')->with('success', $residents->count() . ' sakine başarıyla aidat borcu oluşturuldu.');
*/
        foreach ($residents as $resident) {
            $unit = $resident->unit;
            if (!$unit) continue; // Birime atanmamışsa atla

            $block = $unit->block;
            $site = $block->site;

            // Hiyerarşik olarak en uygun şablonu bul
            // 1. Önce birime özel bir şablon var mı diye bak
            $template = FeeTemplate::where('applicable_type', 'App\Models\Unit')
                ->where('applicable_id', $unit->id)
                ->first();

            // 2. Yoksa, bloğa özel bir şablon var mı diye bak
            if (!$template) {
                $template = FeeTemplate::where('applicable_type', 'App\Models\Block')
                    ->where('applicable_id', $block->id)
                    ->first();
            }

            // 3. O da yoksa, site geneli bir şablon var mı diye bak
            if (!$template) {
                $template = FeeTemplate::where('applicable_type', 'App\Models\Site')
                    ->where('applicable_id', $site->id)
                    ->first();
            }

            // Uygun bir şablon bulunduysa ve tutarı 0'dan büyükse aidat oluştur
            if ($template && $template->amount > 0) {
                Fee::create([
                    'site_id' => $site->id,
                    'block_id' => $block->id,
                    'apartment_id' => $unit->id, // Daire yerine unit_id olmalı
                    'user_id' => $resident->id,
                    'description' => $validated['description'],
                    'amount' => $template->amount,
                    'due_date' => $validated['due_date'],
                ]);
                $feesCreatedCount++;
            }
            // Not: amount = 0 olan bir şablon, o birimin aidattan muaf olduğu anlamına gelir.
        }

        return redirect()->route('fees.index')
            ->with('success', $feesCreatedCount . ' sakine başarıyla aidat borcu oluşturuldu.');

        // Bu mantığı Bölüm 2'de detaylandıracağız.
        // Şimdilik sadece bir yönlendirme yapalım.
        // ... toplu aidat oluşturma kodları buraya gelecek ...

        //return redirect()->route('fees.index')->with('success', 'Toplu aidat oluşturma işlemi başlatıldı.');
    }
}
