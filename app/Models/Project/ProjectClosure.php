<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectClosure extends Model {
    protected $table = 'dotp_post_mortem_analysis';
    protected $primaryKey = 'pma_id';
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'project_start_date',
        'project_end_date',
        'project_planned_start_date',
        'project_planned_end_date',
        'project_meeting_date',
        'planned_budget',
        'budget',
        'participants',
        'project_strength',
        'project_weaknesses',
        'improvement_suggestions',
        'conclusions'
    ];

    protected $casts = [
        'project_meeting_date' => 'date',
        'project_planned_start_date' => 'date',
        'project_start_date' => 'date',
        'project_planned_end_date' => 'date',
        'project_end_date' => 'date',
        'planned_budget' => 'float',
        'budget' => 'float',
    ];
}
