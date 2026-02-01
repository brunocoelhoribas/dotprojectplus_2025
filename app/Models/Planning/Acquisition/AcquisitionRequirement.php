<?php

namespace App\Models\Planning\Acquisition;

use Illuminate\Database\Eloquent\Model;

class AcquisitionRequirement extends Model {
    protected $table = 'dotp_acquisition_planning_requirements';
    public $timestamps = false;

    protected $fillable = ['acquisition_id', 'requirement'];
}
