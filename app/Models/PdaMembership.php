<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdaMembership extends Model
{
    // 🛠️ Added both 'payment_status' and 'status' to completely bypass mass assignment blocks
    protected $fillable = [
        'dentist_profile_id', 
        'membership_year', 
        'payment_status',
        'status'
    ];

    /**
     * Defines the reverse relationship: This log belongs to a specific dentist
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(DentistProfile::class, 'dentist_profile_id');
    }
}