<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'profile_image',
        'phone',
        'address',
        'sector',
        'road',
        'password',
        'confirm_password',
        'role',
        'monthly_payment_status',
        'subscription_valid_until',
        'last_transaction_id',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWasteCollector(): bool
    {
        return $this->role === 'waste_collector';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

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
        ];
    }

    public function collectionRequests()
    {
        return $this->hasMany(CollectionRequest::class, 'collector_id');
    }

    public function requests()
    {
        return $this->hasMany(CollectionRequest::class, 'user_id');
    }

    public function dutyRosters()
    {
        return $this->hasMany(DutyRoster::class, 'collector_id');
    }

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class, 'user_id');
    }

    public function collectorProfile()
    {
        return $this->hasOne(CollectorProfile::class, 'user_id');
    }
}
