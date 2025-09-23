<?php

namespace Modules\Finance\App\Policies;

use App\Models\User;
use Modules\Finance\App\Models\Budget;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetPolicy
{
    use HandlesAuthorization;

    /**
     * Index sayfasını kimlerin görebileceğini belirler.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage budgets');
    }

    /**
     * Tek bir bütçeyi kimlerin görebileceğini belirler.
     */
    public function view(User $user, Budget $budget): bool
    {
        return $user->can('manage budgets');
    }

    /**
     * Kimlerin bütçe oluşturabileceğini belirler.
     */
    public function create(User $user): bool
    {
        return $user->can('manage budgets');
    }

    /**
     * Kimlerin bütçe güncelleyebileceğini belirler.
     */
    public function update(User $user, Budget $budget): bool
    {
        return $user->can('manage budgets');
    }

    /**
     * Kimlerin bütçe silebileceğini belirler.
     */
    public function delete(User $user, Budget $budget): bool
    {
        return $user->can('manage budgets');
    }
}
