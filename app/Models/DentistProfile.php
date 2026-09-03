<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DentistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name', 
        'profile_image', 
        'prc_no', 
        'date_of_birth', 
        'home_address', 
        'clinic_address', 
        'email_address', 
        'contact_no'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(PdaMembership::class, 'dentist_profile_id');
    }
}