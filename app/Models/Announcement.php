<?php

namespace App\Models;

use App\Models\Scopes\ManagedScope; // Scope'u import et
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['published_at' => 'datetime'];

    // ManagedScope, yöneticilerin sadece kendi sitelerinin duyurularını görmesini sağlar.
    protected static function booted(): void
    {
        static::addGlobalScope(new ManagedScope);
    }

    public function site() { return $this->belongsTo(Site::class); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }
    public function targetRoles()
    {
        return $this->belongsToMany(Role::class, 'announcement_role');
    }

    public function targetUsers()
    {
        return $this->belongsToMany(User::class, 'announcement_user');
    }
    public function reads()
    {
        return $this->belongsToMany(User::class, 'announcement_reads', 'announcement_id', 'user_id')->withTimestamps();
    }
}
