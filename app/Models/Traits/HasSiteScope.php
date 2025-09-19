<?php

namespace App\Models\Traits;

use App\Models\Scopes\SiteScope;

trait HasSiteScope
{
    protected static function bootHasSiteScope(): void
    {
        static::addGlobalScope(new SiteScope());
    }
}
