<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
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
        'profile_image',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship to the Dentist Profile
     */
    public function profile(): HasOne
    {
        return $this->hasOne(DentistProfile::class, 'user_id');
    }

    /**
     * Relationship to Memberships (via the profile)
     */
    public function memberships(): HasManyThrough
    {
        // This bridges the gap between User and PdaMembership via DentistProfile
        return $this->hasManyThrough(
            PdaMembership::class, 
            DentistProfile::class, 
            'user_id',            // Foreign key on dentist_profiles table
            'dentist_profile_id', // Foreign key on pda_memberships table
            'id',                 // Local key on users table
            'id'                  // Local key on dentist_profiles table
        );
    }
}