<?php

namespace App\Models\Initiating;

use App\Models\User\UserContact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model for the 'initiating_stakeholder' pivot table.
 * (Based on setup.php)
 */
class InitiatingStakeholder extends Model {
    protected $table = 'dotp_initiating_stakeholder';
    protected $primaryKey = 'initiating_stakeholder_id';

    /**
     * This pivot model does not have timestamps.
     */
    public $timestamps = false;

    /**
     * Fillable fields based on setup.php
     */
    protected $fillable = [
        'initiating_id',
        'contact_id',
        'stakeholder_responsibility',
        'stakeholder_interest',
        'stakeholder_power',
        'stakeholder_strategy',
    ];

    /**
     * Get the main charter (initiating document) this stakeholder belongs to.
     */
    public function initiating(): BelongsTo {
        return $this->belongsTo(Initiating::class, 'initiating_id', 'initiating_id');
    }

    /**
     * Get the contact details for this stakeholder.
     */
    public function contact(): BelongsTo {
        return $this->belongsTo(UserContact::class, 'contact_id', 'contact_id');
    }
}
