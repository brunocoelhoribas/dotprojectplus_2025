<?php

namespace App\Models\Planning\Quality;

use Illuminate\Database\Eloquent\Model;

class QualityRequirement extends Model {
    protected $table = 'dotp_quality_control_requirement';
    public $timestamps = false;

    protected $fillable = [
        'quality_planning_id',
        'requirement'
    ];
}
