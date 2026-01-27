<?php

namespace App\Models\Project;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRisk extends Model {
    protected $table = 'dotp_risks';
    protected $primaryKey = 'risk_id';
    public $timestamps = false;

    protected $fillable = [
        'risk_name',
        'risk_cause',
        'risk_consequence',
        'risk_responsible',
        'risk_description',
        'risk_probability',
        'risk_impact',
        'risk_answer_to_risk',
        'risk_project',
        'risk_task',
        'risk_notes',
        'risk_potential_other_projects',
        'risk_lessons_learned',
        'risk_priority',
        'risk_active',
        'risk_strategy',
        'risk_prevention_actions',
        'risk_contingency_plan',
        'risk_period_start_date',
        'risk_period_end_date',
        'risk_status',
        'risk_ear_classification',
        'risk_triggers',
        'risk_is_contingency'
    ];

    public const ACTIVE = 1;
    public const INACTIVE = 0;
    public const THRESHOLD_HIGH_PRIORITY = 6;

    protected $casts = [
        'risk_period_start_date' => 'date',
        'risk_period_end_date' => 'date',
        'risk_active' => 'integer',
        'risk_probability' => 'integer',
        'risk_impact' => 'integer',
    ];

    public const LEVELS = [
        1 => 'low',
        2 => 'medium',
        3 => 'high',
        4 => 'very_high'
    ];

    public const STATUSES = [
        0 => 'identified',
        1 => 'monitored',
        2 => 'occurred',
        3 => 'closed'
    ];

    public const STRATEGIES = [
        0 => 'accept',
        1 => 'avoid',
        2 => 'mitigate',
        3 => 'transfer'
    ];

    public const EAR_CLASSIFICATIONS = [
        1 => 'organizational',
        2 => 'technical',
        3 => 'external',
        4 => 'pm'
    ];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'risk_project', 'project_id');
    }

    public function responsible(): BelongsTo {
        return $this->belongsTo(User::class, 'risk_responsible', 'user_id');
    }

    public function getProbabilityTextAttribute(): string {
        return self::LEVELS[$this->risk_probability] ?? 'low';
    }

    public function getImpactTextAttribute(): string {
        return self::LEVELS[$this->risk_impact] ?? 'low';
    }

    public function getRiskExposureAttribute(): string {
        $score = $this->risk_probability * $this->risk_impact;

        if ($score >= 12) {
            return 'very_high';
        }
        if ($score >= 6) {
            return 'high';
        }
        if ($score >= 3) {
            return 'medium';
        }

        return 'low';
    }

    public function getExposureFactorLevelAttribute(): string {
        return match ($this->risk_exposure) {
            'very_high', 'high' => 'bg-danger text-white',
            'medium' => 'bg-warning text-dark',
            'low' => 'bg-success text-white',
            default => ''
        };
    }

    public function getStatusKeyAttribute(): string {
        return self::STATUSES[(int)$this->risk_status] ?? '';
    }

    public function getStrategyKeyAttribute(): string {
        return self::STRATEGIES[(int)$this->risk_strategy] ?? '';
    }

    public function scopeActive($query) {
        return $query->where('risk_active', self::ACTIVE);
    }

    public function scopeInactive($query) {
        return $query->where('risk_active', self::INACTIVE);
    }

    public function scopeByProject($query, $projectId) {
        return $query->where('risk_project', $projectId);
    }

    public function getScoreAttribute(): int {
        return (int) $this->risk_probability * (int) $this->risk_impact;
    }

    public function isHighPriority(): bool {
        return $this->score >= self::THRESHOLD_HIGH_PRIORITY;
    }
}
