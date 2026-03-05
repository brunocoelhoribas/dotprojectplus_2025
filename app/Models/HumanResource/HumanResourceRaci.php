<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanResourceRaci extends Model {
    protected $table = 'dotp_raci';

    protected $fillable = [
        'human_resource_id',
        'project_id',
        'activity_name',
        'raci_role'
    ];

    public function humanResource(): BelongsTo {
        return $this->belongsTo(HumanResource::class, 'human_resource_id', 'human_resource_id');
    }
}
