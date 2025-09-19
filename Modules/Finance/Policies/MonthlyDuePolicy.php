<?php

namespace Modules\Finance\app\Policies;

use App\Models\User;
use Modules\Finance\app\Models\MonthlyDue;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
use App\Policies\Traits\SuperAdminOverride; // Trait'i ekle
use App\Policies\Traits\AuthorizesSiteResources; // Trait'i ekle

class MonthlyDuePolicy
{
    // Sadece Trait'leri kullanarak DRY ve SOLID prensiplerini uygula
    use SuperAdminOverride, AuthorizesSiteResources;
    /**
     * Diğer tüm izin metodlarından önce çalışır.
     * Eğer kullanıcı 'super_admin' rolüne sahipse, başka hiçbir kontrol yapmadan
     * anında tam yetki verir (true döndürür).
     *
     * @param \App\Models\User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    /**
     * İzin reddetme mesajını merkezileştiren ve izin adını ekleyen özel bir metot.
     * @param string $ability Eksik olan iznin adı (metot adı)
     * @return \Illuminate\Auth\Access\Response
     */
    private function denyWithAbility(string $ability): Response
    {
        // Geliştirici için teknik bilgi içeren, production'da ise daha genel bir mesaj gösterilebilir.
        // App::isProduction() ? 'Bu işlem için yetkiniz yok.' : "..."
        return Response::deny("Bu işlemi yapma yetkiniz bulunmamaktadır. (Gerekli İzin: {$ability})");
    }
    /**
     * İzin reddetme mesajını merkezileştirir ve detaylı log kaydı oluşturur.
     */
    private function deny(User $user, string $ability, ?string $customMessage = null): Response
    {
        $routeName = request()->route()->getName();
        $message = $customMessage ?? "Bu işlemi yapma yetkiniz bulunmamaktadır.";

        // Geliştiricinin sorunu anında teşhis etmesi için detaylı loglama
        Log::warning("Yetkisiz Erişim Denemesi", [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'route' => $routeName,
            'permission_checked' => $ability,
        ]);

        // Kullanıcıya gösterilecek hata mesajı
        $userMessage = "{$message} (Gerekli İzin: {$ability})";

        return Response::deny($userMessage);
    }
    public function viewAny(User $user): Response
    {
        return $user->hasAnyRole(['site-admin', 'property-owner', 'accountant'])
            ? Response::allow()
            : $this->denyWithAbility('viewAny'); // Metot adını otomatik olarak mesaja ekle
    }

    public function view(User $user, MonthlyDue $monthlyDue): Response
    {
        return $user->site_id === $monthlyDue->site_id
            ? Response::allow()
            : $this->denyWithAbility('view');
    }

    public function create(User $user): Response
    {

        return $user->hasAnyRole(['site-admin', 'property-owner', 'accountant'])
            ? Response::allow()
            : $this->denyWithAbility('create');
    }

    public function update(User $user, MonthlyDue $monthlyDue): Response
    {
        // Önce görme (ve dolayısıyla sahip olma) yetkisini kontrol et
        $viewResponse = $this->view($user, $monthlyDue);
        if ($viewResponse->denied()) {
            return $this->deny($user, 'update', 'Sadece kendi sitenize ait aidatları güncelleyebilirsiniz.');
        }
        return $this->view($user, $monthlyDue); // Önce görme yetkisini (ve sahip kontrolünü) denetle
    }

    public function delete(User $user, MonthlyDue $monthlyDue): Response
    {
        $viewResponse = $this->view($user, $monthlyDue);
        if ($viewResponse->denied()) {
            return $this->deny($user, 'delete', 'Sadece kendi sitenize ait aidatları silebilirsiniz.');
        }
        return $this->view($user, $monthlyDue); // Önce görme yetkisini (ve sahip kontrolünü) denetle
    }
}
