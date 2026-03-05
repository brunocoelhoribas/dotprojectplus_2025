<?php

namespace App\Models\Project\Task;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model {
    protected $table = 'dotp_task_log';
    protected $primaryKey = 'task_log_id';
    public $timestamps = false;

    protected $fillable = [
        'task_log_task',
        'task_log_name',
        'task_log_description',
        'task_log_creator',
        'task_log_hours',
        'task_log_date',
        'task_log_costcode'
    ];

    protected $casts = [
        'task_log_date' => 'datetime',
        'task_log_hours' => 'float',
    ];

    public function task(): BelongsTo {
        return $this->belongsTo(Task::class, 'task_log_task', 'task_id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'task_log_creator', 'user_id');
    }
}
