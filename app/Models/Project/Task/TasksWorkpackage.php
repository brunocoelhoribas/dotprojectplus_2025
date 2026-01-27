<?php

namespace App\Models\Project\Task;

use Illuminate\Database\Eloquent\Model;

class TasksWorkpackage extends Model {
    protected $table = 'dotp_tasks_workpackages';
    protected $primaryKey = 'task_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'task_id',
        'eap_item_id'
    ];
}
