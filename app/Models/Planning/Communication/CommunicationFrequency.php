<?php

namespace App\Models\Planning\Communication;

use Illuminate\Database\Eloquent\Model;

class CommunicationFrequency extends Model {
    protected $table = 'dotp_communication_frequency';
    protected $primaryKey = 'communication_frequency_id';
    public $timestamps = false;

    protected $fillable = ['communication_frequency', 'communication_frequency_hasdate'];
}
