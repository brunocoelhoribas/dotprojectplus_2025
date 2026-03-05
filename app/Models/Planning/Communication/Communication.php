<?php

namespace App\Models\Planning\Communication;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Communication extends Model {
    protected $table = 'dotp_communication';
    protected $primaryKey = 'communication_id';
    public $timestamps = false;

    protected $fillable = [
        'communication_title',
        'communication_information',
        'communication_frequency_id',
        'communication_channel_id',
        'communication_project_id',
        'communication_restrictions',
        'communication_date',
        'communication_responsible_authorization'
    ];

    public function channel(): BelongsTo {
        return $this->belongsTo(CommunicationChannel::class, 'communication_channel_id', 'communication_channel_id');
    }

    public function frequency(): BelongsTo {
        return $this->belongsTo(CommunicationFrequency::class, 'communication_frequency_id', 'communication_frequency_id');
    }

    public function issuers(): HasMany {
        return $this->hasMany(CommunicationIssuing::class, 'communication_id', 'communication_id');
    }

    public function receptors(): HasMany {
        return $this->hasMany(CommunicationReceptor::class, 'communication_id', 'communication_id');
    }
}
