<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;   // <-- Gerekli olabilir, ekleyin
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Gerekli olabilir, ekleyin
use Illuminate\Database\Eloquent\SoftDeletes;


class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'block_id',
        'name_or_number',
        'floor',
        'type',
        'properties',
        'deed_status',
        'owner_id'
    ];
    protected $guarded = [];
    protected $casts = [
        'properties' => 'array',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
    public function residents(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function parkingSpaces()
    {
        return $this->hasMany(ParkingSpace::class);
    }
    // Unit bir siteye bağlı
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Unit içinde yaşayan kullanıcı (örn. resident_id ile)
    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    
}
