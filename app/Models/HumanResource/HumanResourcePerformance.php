<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanResourcePerformance extends Model {
    protected $table = 'dotp_human_resource_performance';

    protected $fillable = [
        'company_id',
        'human_resource_id',
        'performance_score',
        'potential_score',
        'facilitator_notes',
        'evaluation_date'
    ];

    protected function casts(): array {
        return [
            'evaluation_date' => 'date',
        ];
    }

    public function humanResource(): BelongsTo {
        return $this->belongsTo(HumanResource::class, 'human_resource_id', 'human_resource_id');
    }
}
