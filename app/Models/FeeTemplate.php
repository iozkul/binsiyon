<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Şablonun uygulandığı modeli (Site, Block, veya Unit) getiren polimorfik ilişki.
     */
    public function applicable()
    {
        return $this->morphTo();
    }
}
