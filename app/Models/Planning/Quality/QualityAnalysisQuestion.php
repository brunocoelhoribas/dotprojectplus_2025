<?php

namespace App\Models\Planning\Quality;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityAnalysisQuestion extends Model {
    protected $table = 'dotp_quality_control_analiysis_question';
    public $timestamps = false;

    protected $fillable = [
        'goal_id',
        'question',
        'target'
    ];

    public function metrics(): HasMany {
        return $this->hasMany(QualityMetric::class, 'question_id', 'id');
    }
}
