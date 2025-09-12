<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['block_id','number','type','occupants'];
    public function block() { return $this->belongsTo(Block::class); }
    public function residents() { return $this->hasMany(Resident::class); }
}
