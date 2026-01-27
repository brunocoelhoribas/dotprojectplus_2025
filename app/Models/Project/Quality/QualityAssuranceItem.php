<?php

namespace App\Models\Project\Quality;

use Illuminate\Database\Eloquent\Model;

class QualityAssuranceItem extends Model {
    protected $table = 'dotp_quality_assurance_item';
    public $timestamps = false;

    protected $fillable = [
        'quality_planning_id',
        'what',
        'who',
        'when',
        'how'
    ];
}
