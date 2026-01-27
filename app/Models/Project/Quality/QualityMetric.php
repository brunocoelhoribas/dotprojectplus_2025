<?php

namespace App\Models\Project\Quality;

use Illuminate\Database\Eloquent\Model;

class QualityMetric extends Model {
    protected $table = 'dotp_quality_control_metric';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'metric',
        'how_to_collect'
    ];
}
