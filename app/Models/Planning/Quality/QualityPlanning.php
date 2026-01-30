<?php

namespace App\Models\Planning\Quality;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityPlanning extends Model {
    protected $table = 'dotp_quality_planning';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'quality_controlling',
        'quality_assurance',
        'quality_policies'
    ];

    public function requirements(): HasMany {
        return $this->hasMany(QualityRequirement::class, 'quality_planning_id', 'id');
    }

    public function assuranceItems(): HasMany {
        return $this->hasMany(QualityAssuranceItem::class, 'quality_planning_id', 'id');
    }

    public function goals(): HasMany {
        return $this->hasMany(QualityGoal::class, 'quality_planning_id', 'id');
    }
}
