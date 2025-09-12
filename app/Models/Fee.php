<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\ManagedScope;

class Fee extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted(): void
    {
        static::addGlobalScope(new ManagedScope);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bir aidat borcu, bir birime aittir.
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'apartment_id'); // 'apartment_id' sütun adını 'unit_id' olarak değiştirmeyi unutmayın!
    }

    // Bir aidat borcunun birden çok ödemesi olabilir.
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function site() { return $this->belongsTo(Site::class); } // Site ilişkisini ekleyin
}
