<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTraining extends Model {
    protected $table = 'dotp_need_for_training';
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'description'
    ];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
