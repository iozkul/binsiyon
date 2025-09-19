<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class StaffMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'site_id', 'job_title', 'start_date', 'end_date',
        'salary_type', 'salary_amount', 'iban', 'tckn', 'sgk_details', 'is_active',
    ];

    protected $casts = [
        'salary_amount' => 'decimal:2',
        'sgk_details' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    // TCKN verisini şifrele/çöz
    protected function tckn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decryptString($value) : null,
            set: fn ($value) => $value ? Crypt::encryptString($value) : null,
        );
    }
}
