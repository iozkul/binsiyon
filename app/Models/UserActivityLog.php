<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // İşlemi yapan admin
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    // İşlem yapılan kullanıcı
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
