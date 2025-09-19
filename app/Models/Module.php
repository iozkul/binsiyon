<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function sites()
    {
        return $this->belongsToMany(Site::class, 'module_site');
    }
}
