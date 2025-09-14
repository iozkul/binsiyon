<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\ManagedScope;
use App\Models\Debt;


class Site extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new ManagedScope);

    }
    use HasFactory, SoftDeletes;
    //protected $fillable = ['name','address'];
	protected $guarded = [];
    public function blocks() { return $this->hasMany(Block::class); }
	public function units()
    {
        return $this->hasManyThrough(Unit::class, Block::class);
    }
    protected $fillable = [
        'name',
        'country',
        'city',
        'district',
        'address_line',
        'postal_code',
    ];
    public function debts()
    {
        return $this->hasManyThrough(Debt::class, User::class);
    }
    public function managers()
    {
        return $this->belongsToMany(User::class, 'site_manager');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'site_user');
    }
}
