<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'name',
        'brand',
        'model',
        'purchase_date',
        'warranty_end_date',
        'maintenance_interval_days',
        'last_maintenance_date',
        'next_maintenance_date',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'warranty_end_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
    ];

    /**
     * Get the site that owns the fixture.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
