<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdaMembership extends Model
{
    protected $fillable = [
        'dentist_profile_id', 'membership_year', 'status'
    ];

    // Defines the reverse relationship: This log belongs to a specific dentist
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(DentistProfile::class, 'dentist_profile_id');
    }
}