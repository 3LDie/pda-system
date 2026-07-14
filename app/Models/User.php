<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 
    'email', 
    'password', 
    'role', 
    'full_name', 
    'prc_no', 
    'date_of_birth', 
    'contact_no', 
    'home_address', 
    'clinic_address', 
    'profile_image'
    'must_change_password'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    /**
     * Get all membership payment logs associated with this user/member.
     */
    public function memberships(): HasMany
    {
        // ✅ FIXED: Points directly to your true PdaMembership model class
        return $this->hasMany(PdaMembership::class); 
    }
}