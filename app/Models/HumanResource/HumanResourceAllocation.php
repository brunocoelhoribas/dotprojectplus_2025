<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanResourceAllocation extends Model {
    protected $table = 'dotp_human_resource_allocation';

    public function humanResource(): BelongsTo {
        return $this->belongsTo(HumanResource::class, 'human_resource_id', 'human_resource_id');
    }
}
