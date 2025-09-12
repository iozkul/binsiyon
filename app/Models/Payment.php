<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ManagedScope;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted(): void
    {
        static::addGlobalScope(new ManagedScope);
    }

    // Bir ödeme, bir kullanıcı tarafından yapılır.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bir ödeme, bir aidat borcuna aittir.
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
    public function site() { return $this->belongsTo(Site::class); } // Site ilişkisini ekleyin
}
