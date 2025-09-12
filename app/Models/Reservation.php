<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class Reservation extends Model
{
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
