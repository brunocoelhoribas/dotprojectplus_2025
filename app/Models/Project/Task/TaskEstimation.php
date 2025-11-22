<?php

namespace App\Models\Project\Task;

use Illuminate\Database\Eloquent\Model;

class TaskEstimation extends Model {
    protected $table = 'dotp_project_tasks_estimations';
    protected $fillable = [
        'task_id',
        'effort',
        'effort_unit',
        'duration'
    ];
    public $timestamps = false;

    // You will add relationships to 'ProjectTaskEstimatedRole' here later
}
