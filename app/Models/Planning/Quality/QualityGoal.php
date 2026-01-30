<?php

namespace App\Models\Planning\Quality;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityGoal extends Model
{
    protected $table = 'dotp_quality_control_goal';
    public $timestamps = false;

    protected $fillable = [
        'quality_planning_id',
        'gqm_goal_object',
        'gqm_goal_propose',
        'gqm_goal_respect_to',
        'gqm_goal_point_of_view',
        'gqm_goal_context'
    ];

    public function questions(): HasMany {
        return $this->hasMany(QualityAnalysisQuestion::class, 'goal_id', 'id');
    }
}
