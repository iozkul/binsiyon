<?php

namespace Modules\Finance\app\Policies;

use App\Models\User;
use Modules\Finance\app\Models\MonthlyDue;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
class MonthlyDuePolicy
{
    use HandlesAuthorization;

    // Super Admin tüm yetkilere sahiptir.
    public function before(User $user, $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
    }

    public function viewAny(User $user): Response
    {
        return $user->hasAnyRole(['site_owner', 'block_admin', 'accountant'])
            ? Response::allow()
            : Response::deny('Aidatları listelemek için gerekli role sahip değilsiniz.');
    }

    public function view(User $user, MonthlyDue $monthlyDue): Response
    {
        // Site yöneticisi, aidatın kendi sitesine ait olup olmadığını kontrol eder.
        if ($user->hasRole('site_owner')) {
            // return $user->managedSites()->where('id', $monthlyDue->site_id)->exists();
            return true; // TODO: ManagedSites ilişkisi kurulana kadar geçici izin.
        }

        // Blok yöneticisi...
        if ($user->hasRole('block_admin')) {
            // return $user->managedBlocks()->where('id', $monthlyDue->apartment->block_id)->exists();
            return true;
        }

        return false;
    }

    public function create(User $user): Response
    {
        return $user->hasAnyRole(['site_owner', 'accountant'])
            ? Response::allow()
            : Response::deny('Yeni aidat oluşturma yetkiniz bulunmamaktadır.');
    }

    public function update(User $user, MonthlyDue $monthlyDue): bool
    {
        return $this->view($user, $monthlyDue); // Görebiliyorsa güncelleyebilir.
    }

    public function delete(User $user, MonthlyDue $monthlyDue): bool
    {
        return $this->view($user, $monthlyDue); // Görebiliyorsa silebilir.
    }
}
