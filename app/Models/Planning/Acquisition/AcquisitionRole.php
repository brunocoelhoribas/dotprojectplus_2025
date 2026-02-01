<?php

namespace App\Models\Planning\Acquisition;

use Illuminate\Database\Eloquent\Model;

class AcquisitionRole extends Model {
    protected $table = 'dotp_acquisition_planning_roles';
    public $timestamps = false;

    protected $fillable = ['acquisition_id', 'role', 'responsability'];
}
