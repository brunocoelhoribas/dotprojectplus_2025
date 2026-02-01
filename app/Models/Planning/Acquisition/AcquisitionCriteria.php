<?php

namespace App\Models\Planning\Acquisition;

use Illuminate\Database\Eloquent\Model;

class AcquisitionCriteria extends Model {
    protected $table = 'dotp_acquisition_planning_criteria';
    public $timestamps = false;

    protected $fillable = ['acquisition_id', 'criteria', 'weight'];
}
