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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use App\Models\DentistProfile;

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
     * Send the email verification notification via Brevo HTTP API to bypass Railway SMTP port blocks.
     */
    public function sendEmailVerificationNotification()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );

        Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => env('MAIL_FROM_NAME', 'PDA Portal'),
                'email' => env('MAIL_FROM_ADDRESS', 'pda.portal.2026@gmail.com')
            ],
            'to' => [
                ['email' => $this->email]
            ],
            'subject' => 'Verify your email address',
            'htmlContent' => '<p>Hello!</p><p>Please click the link below to verify your email address:</p><p><a href="' . $verificationUrl . '">Verify Email Address</a></p>'
        ]);
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