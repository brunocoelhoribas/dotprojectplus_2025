<?php

namespace App\Models\Project;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectMinute extends Model {
    protected $table = 'dotp_project_minutes';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'minute_date',
        'description',
        'participants',
        'isEffort',
        'isDuration',
        'isResource',
        'isSize'
    ];

    protected $casts = [
        'minute_date' => 'datetime',
        'isEffort' => 'boolean',
        'isDuration' => 'boolean',
        'isResource' => 'boolean',
        'isSize' => 'boolean',
    ];

    public function members(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'dotp_task_minute_members',
            'task_minute_id',
            'user_id'
        );
    }
}
