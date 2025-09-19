<?php

namespace Modules\Finance\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Site;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Scopes\FinanceScope;

class MonthlyDue extends Model
{
    use HasFactory;

    protected $table = 'monthly_dues';

    protected $fillable = [
        'site_id',
        'apartment_id',
        'resident_user_id',
        'period',
        'amount',
        'due_date',
        'status',
    ];
    protected static function booted(): void
    {
        // super_admin dışındaki tüm kullanıcılar için bu scope'u otomatik uygula
        static::addGlobalScope(new FinanceScope);
    }

    protected $casts = [
        'period' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
