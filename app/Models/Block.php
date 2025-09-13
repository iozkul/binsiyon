<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\SiteAdminScope;
use App\Models\Scopes\ManagedScope;

class Block extends Model
{
     use HasFactory, SoftDeletes;
    protected static function booted()
    {
        // Bu scope, Block modeli için yapılan TÜM sorgulara otomatik olarak uygulanır.
        static::addGlobalScope(new SiteAdminScope);
    }

    protected $fillable = ['site_id','name', 'manager_id'];
    public function site() { return $this->belongsTo(Site::class); }
    //public function apartments(): HasMany   {        return $this->hasMany(Apartment::class);    }

    public function units() { return $this->hasMany(Unit::class); }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function admins()
    {
        return $this->belongsToMany(User::class, 'block_admins', 'block_id', 'user_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'block_user_pivot', 'block_id', 'user_id');
    }
// Bloktaki tüm unit sahipleri (sakinler)
    public function residents()
    {
        return $this->hasManyThrough(
            User::class,
            Unit::class,
            'block_id',   // Unit tablosundaki foreign key
            'id',         // User tablosundaki primary key
            'id',         // Block tablosundaki local key
            'owner_id'    // Unit tablosundaki foreign key
        );
    }


}
