<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Str;

class User extends Authenticatable
{
    use HasApiTokens;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType   = 'int';

    public function canAccessPanel(Panel $panel): bool
    {
        // Match antara panel ID dan role user
        return $this->role === $panel->getId();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = random_int(100000, 999999);
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'password'          => 'hashed',
        ];
    }

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function isProfileComplete(): bool
    {
        return $this->detail &&
            !empty($this->detail->phone) &&
            !empty($this->detail->address) &&
            !empty($this->detail->birthdate) &&
            !empty($this->detail->id_card);
    }
}
