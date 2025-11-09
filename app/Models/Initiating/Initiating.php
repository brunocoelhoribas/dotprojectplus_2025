<?php

namespace App\Models\Initiating;

use App\Models\Project\Project;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model for the existing 'initiating' table (Termo de Abertura)
 * Based on initiating.class.php
 */
class Initiating extends Model {
    use HasFactory;

    protected $table = 'dotp_initiating';
    protected $primaryKey = 'initiating_id';

    /**
     * This model does not use 'created_at' and 'updated_at'.
     * It has 'initiating_date_create' but we'll manage it manually if needed.
     */
    public $timestamps = false;

    /**
     * Fillable fields based on addedit.php and do_initiating_aed.php
     */
    protected $fillable = [
        'project_id',
        'initiating_title',
        'initiating_manager',
        'initiating_create_by',
        'initiating_date_create',
        'initiating_justification',
        'initiating_objective',
        'initiating_expected_result',
        'initiating_premise',
        'initiating_restrictions',
        'initiating_budget',
        'initiating_start_date',
        'initiating_end_date',
        'initiating_milestone',
        'initiating_success', // 'Criteria for success' in pdf.php
        'initiating_approved',
        'initiating_authorized',
        'initiating_completed',
        'initiating_approved_comments',
        'initiating_authorized_comments',
    ];

    /**
     * Get the project that this charter belongs to.
     */
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    /**
     * Get the manager (User) assigned to this charter.
     */
    public function manager(): BelongsTo {
        return $this->belongsTo(User::class, 'initiating_manager', 'user_id');
    }

    public function stakeholders(): HasMany {
        return $this->hasMany(InitiatingStakeholder::class, 'initiating_id', 'initiating_id');
    }
}
