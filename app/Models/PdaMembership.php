<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdaMembership extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pda_memberships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dentist_profile_id', 
        'membership_year', 
        'payment_status',
        'status'
    ];

    /**
     * Defines the reverse relationship: This log belongs to a specific dentist profile.
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(DentistProfile::class, 'dentist_profile_id');
    }
}