<?php

namespace App\Models\Project\Task;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User\User;

/**
 * Represents a Project Activity/Task.
 * Mapped to table 'dotp_tasks'.
 */
class Task extends Model {
    use HasFactory;

    protected $table = 'dotp_tasks';
    protected $primaryKey = 'task_id';
    public $timestamps = false;

    protected $fillable = [
        'task_name',
        'task_parent',
        'task_milestone',
        'task_project',
        'task_owner',
        'task_start_date',
        'task_duration',
        'task_duration_type',
        'task_end_date',
        'task_status',
        'task_priority',
        'task_percent_complete',
        'task_description',
        'task_target_budget',
        'task_related_url',
        'task_creator',
        'task_client_publish',
        'task_dynamic',
        'task_access',
        'task_notify',
        'task_departments',
        'task_contacts',
        'task_custom',
        'task_type',
        'task_order',
    ];

    protected $casts = [
        'task_start_date' => 'datetime',
        'task_end_date' => 'datetime',
    ];

    protected function wbsCode(): Attribute {
        return Attribute::make(
            get: fn() => 'A.' . $this->task_id,
        );
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'task_project', 'project_id');
    }


    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'task_owner', 'user_id');
    }


    public function estimation(): HasOne {
        return $this->hasOne(TaskEstimation::class, 'task_id', 'task_id');
    }

    public function predecessors(): BelongsToMany {
        return $this->belongsToMany(
            __CLASS__,
            'dotp_task_dependencies',
            'dependencies_task_id',
            'dependencies_req_task_id'
        );
    }

    public function resources(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'dotp_user_tasks',
            'task_id',
            'user_id'
        );
    }
}
