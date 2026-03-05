<?php

namespace App\Models\Planning\Communication;

use Illuminate\Database\Eloquent\Model;

class CommunicationChannel extends Model {
    protected $table = 'dotp_communication_channel';
    protected $primaryKey = 'communication_channel_id';
    public $timestamps = false;

    protected $fillable = ['communication_channel'];
}
