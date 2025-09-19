<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BudgetPolicy
{
    /**
     * Bütçe listesini kimlerin görebileceğini belirler.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage budgets');
    }

    /**
     * Belirli bir bütçeyi kimlerin görebileceğini belirler.
     */
    public function view(User $user, Budget $budget): bool
    {
        // Kullanıcı hem yetkiye sahip olmalı hem de bütçe kendi sitesine ait olmalı.
        return $user->can('manage budgets') && $user->site_id === $budget->site_id;
    }

    /**
     * Kimlerin yeni bütçe oluşturabileceğini belirler.
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
        return $user->can('manage budgets') && $user->site_id === $budget->site_id;
    }

    /**
     * Kimlerin bütçe silebileceğini belirler.
     */
    public function delete(User $user, Budget $budget): bool
    {
        return $user->can('manage budgets') && $user->site_id === $budget->site_id;
    }
}
