<?php

namespace App\Models\Monitoring;

use App\Models\Project\Task\Task;
use Illuminate\Database\Eloquent\Model;

class MonitoringBaselineTask extends Model {
    protected $table = 'dotp_monitoring_baseline_task';
    public $timestamps = false;
    protected $primaryKey = 'baseline_task_id';

    protected $fillable = [
        'baseline_id',
        'task_id',
        'task_start_date',
        'task_duration',
        'task_duration_type',
        'task_hours_worked',
        'task_end_date',
        'task_percent_complete'
    ];

    protected $casts = [
        'task_start_date' => 'datetime',
        'task_end_date' => 'datetime',
        'task_percent_complete' => 'integer',
        'task_duration' => 'float'
    ];

    public function originalTask() {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }
}
