<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Site;
use App\Models\Scopes\ManagedScope;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'education_status',
        'is_email_confirmed',
        'is_banned_by_admin',
        'first_name', // EKLENDİ
        'last_name',  // EKLENDİ
        'address',        // EKLENDİ
        'city',           // EKLENDİ
        'district',       // EKLENDİ

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_email_confirmed' => 'boolean',
            'is_banned_by_admin' => 'boolean',
            'is_admin' => 'boolean',

        ];
    }

    /**
     * Bu scope, kullanıcı listesi sorgularını mevcut yöneticinin yetkilerine göre filtreler.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeManaged($query)
    {
        $user = auth()->user();

        // Kullanıcı giriş yapmış ve super-admin değilse filtrele
        if ($user && !$user->hasRole('super-admin')) {
            if ($user->hasRole('site-admin')) {
                $managedSiteIds = $user->managedSites()->pluck('sites.id');
                return $query->whereIn('site_id', $managedSiteIds);
            }

            if ($user->hasRole('block-admin')) {
                $managedBlockIds = $user->managedBlocks()->pluck('blocks.id');
                return $query->whereHas('unit', function ($q) use ($managedBlockIds) {
                    $q->whereIn('block_id', $managedBlockIds);
                });
            }
        }

        // Eğer super-admin ise veya kullanıcı girişi yoksa hiçbir filtreleme yapma
        return $query;
    }

    public function site(): BelongsTo
    {
        // 'users' tablosunda 'site_id' adında bir sütun olduğunu varsayar.
        return $this->belongsTo(Site::class);
    }

    /**
     * YENİ EKLENEN METOT
     * Bir kullanıcının ait olduğu bloğu tanımlayan ilişki.
     */
    public function block(): BelongsTo
    {
        // 'users' tablosunda 'block_id' adında bir sütun olduğunu varsayar.
        return $this->belongsTo(Block::class, 'block_user_pivot', 'user_id', 'block_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
        //return $this->belongsTo(Unit::class);
        // Eğer User tablosunda unit_id yoksa, pivot tabloyu kullanmalısın
    }
    /*
    public function managedSites()
    {
        return $this->belongsToMany(Site::class, 'site_manager');
    }*/

    public function managedSites()
    {
        //return $this->belongsToMany(Site::class, 'site_user');
        return $this->belongsToMany(Site::class, 'site_manager', 'user_id', 'site_id');
    }

    public function managedBlocks()
    {
        //return $this->belongsToMany(Block::class, 'block_user');
        return $this->belongsToMany(Block::class, 'block_admins', 'user_id', 'block_id');
    }
    public function managedUnits()
    {
        return Unit::whereIn('block_id', $this->managedBlocks->pluck('id'));
    }
    public function getManagedBlockIds()
    {
        // İlişkiyi başlatır ve SADECE 'blocks' tablosunun 'id' sütununu seçmesini söyleriz.
        return $this->managedBlocks()->select('blocks.id')->pluck('blocks.id');
    }
    public function ownedUnits()
    {
        return $this->hasMany(Unit::class, 'owner_id');
    }

    public function fees(): HasMany
    {
        // 'fees' tablosundaki 'user_id' sütununu kullanır.
        return $this->hasMany(Fee::class);
    }
    public function conversations() { return $this->belongsToMany(Conversation::class); }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

// Kullanıcının bir özelliğe erişimi olup olmadığını kontrol eder.
    public function hasFeature(string $featureKey): bool
    {
        // Eğer paketi yoksa veya paketi pasifse, erişimi yoktur.
        if (!$this->package || !$this->package->is_active) {
            return false;
        }
        // Paketin özellikleri arasında istenen anahtar var mı diye bakar.
        return $this->package->features()->where('key', $featureKey)->exists();
    }
    public function readAnnouncements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_reads', 'user_id', 'announcement_id')->withTimestamps();
    }
}
