<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SiteScope;

class Expense extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new SiteScope);
    }
}
