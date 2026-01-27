<?php

namespace App\Models\Project\Task;

use Illuminate\Database\Eloquent\Model;

class TaskDependency extends Model {
    protected $table = 'dotp_task_dependencies';
    public $timestamps = false;
    protected $primaryKey = 'dependencies_task_id';

    protected $fillable = [
        'dependencies_task_id',
        'dependencies_req_task_id'
    ];
}
