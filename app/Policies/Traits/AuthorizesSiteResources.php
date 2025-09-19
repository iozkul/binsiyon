<?php

namespace App\Policies\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\Response;

trait AuthorizesSiteResources
{
    /**
     * Kaynağın kullanıcının sitesine ait olup olmadığını kontrol eder.
     * * @param \App\Models\User $user
     * @param \Illuminate\Database\Eloquent\Model $resource Site'a ait olan model (örn: MonthlyDue, Expense)
     * @return \Illuminate\Auth\Access\Response
     */
    protected function authorizeSiteResource(User $user, Model $resource): Response
    {
        return $user->site_id === $resource->site_id
            ? Response::allow()
            : Response::deny('Bu işlemi sadece kendi sitenize ait kaynaklar için yapabilirsiniz.');
    }
}
