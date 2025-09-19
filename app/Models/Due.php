<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Due extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'apartment_id',
        'resident_user_id',
        'period',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'period' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_user_id');
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
