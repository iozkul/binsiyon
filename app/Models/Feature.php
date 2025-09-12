<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'feature_package');
    }
}
