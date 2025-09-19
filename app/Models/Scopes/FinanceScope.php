<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class FinanceScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if ($user && !$user->hasRole('super_admin')) {

            if ($user->hasRole('resident') || $user->hasRole('property_owner')) {
                $builder->where('resident_user_id', $user->id);
            }

            // TODO: Site ve Blok yöneticileri için kapsamlar eklenmeli
            // if ($user->hasRole('site_owner')) {
            //     $builder->whereIn('site_id', $user->managedSites()->pluck('id'));
            // }
        }
    }
}
