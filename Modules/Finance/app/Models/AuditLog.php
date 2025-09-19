<?php

namespace Modules\Finance\app\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'auditable_id',
        'auditable_type',
        'payload_old',
        'payload_new',
        'ip_address',
    ];

    protected $casts = [
        'payload_old' => 'array',
        'payload_new' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
