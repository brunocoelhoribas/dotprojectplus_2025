<?php

namespace App\Models\Planning\Communication;

use Illuminate\Database\Eloquent\Model;

class CommunicationIssuing extends Model {
    protected $table = 'dotp_communication_issuing';
    protected $primaryKey = 'communication_issuing_id';
    public $timestamps = false;

    protected $fillable = [
        'communication_id',
        'communication_stakeholder_id'
    ];
}
