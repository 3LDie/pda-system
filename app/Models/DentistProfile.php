<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DentistProfile extends Model
{
    // Allows us to mass-assign data into these columns safely
    protected $fillable = [
        'full_name', 'prc_no', 'date_of_birth', 'home_address', 'clinic_address', 'email_address', 'contact_no'
    ];

    // Defines the relationship: A dentist has many membership year records
    public function memberships(): HasMany
    {
        return $this->hasMany(PdaMembership::class);
    }
}