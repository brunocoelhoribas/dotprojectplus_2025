<?php

namespace App\Models\Project\Task;

use App\Models\HumanResource\HumanResourceAllocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskEstimatedRole extends Model {
    protected $table = 'dotp_project_tasks_estimated_roles';

    public function allocations(): HasMany {
        return $this->hasMany(HumanResourceAllocation::class, 'project_tasks_estimated_roles_id', 'id');
    }
}
