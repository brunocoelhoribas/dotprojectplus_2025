<?php

namespace App\Models\Planning\Communication;

use Illuminate\Database\Eloquent\Model;

class CommunicationReceptor extends Model {
    protected $table = 'dotp_communication_receptor';
    protected $primaryKey = 'communication_receptor_id';
    public $timestamps = false;

    protected $fillable = [
        'communication_id',
        'communication_stakeholder_id'
    ];
}
