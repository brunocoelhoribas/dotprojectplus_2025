<?php

namespace App\Models\Project;

use App\Models\Company\Company;
use App\Models\Department\Department;
use App\Models\Initiating\Initiating;
use App\Models\Initiating\InitiatingStakeholder;
use App\Models\Planning\Acquisition\AcquisitionPlanning;
use App\Models\Planning\Communication\Communication;
use App\Models\Planning\Quality\QualityPlanning;
use App\Models\Planning\Risk\Risk;
use App\Models\User\User;
use App\Models\User\UserContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model {
    use HasFactory;

    protected $table = 'dotp_projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false;
    protected $fillable = [
        'project_company',
        'project_internal',
        'project_name',
        'project_short_name',
        'project_owner',
        'project_creator',
        'project_url',
        'project_demo_url',
        'project_start_date',
        'project_end_date',
        'project_status',
        'project_percent_complete',
        'project_color_identifier',
        'project_description',
        'project_target_budget',
        'project_departments',
        'project_contacts',
        'project_priority',
        'project_type',
    ];

    protected $casts = [
        'project_start_date' => 'datetime',
        'project_end_date' => 'datetime',
    ];


    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'project_company', 'company_id');
    }


    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'project_owner', 'user_id');
    }


    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'project_creator', 'user_id');
    }

    public function contacts(): BelongsToMany {
        return $this->belongsToMany(
            UserContact::class,
            'dotp_project_contacts',
            'project_id',
            'contact_id'
        );
    }

    public function initiating(): HasOne {
        return $this->hasOne(Initiating::class, 'project_id', 'project_id');
    }

    public function departments(): BelongsToMany {
        return $this->belongsToMany(
            Department::class,
            'dotp_project_departments',
            'project_id',
            'department_id'
        );
    }

    public function wbsItems(): HasMany|self {
        return $this->hasMany(ProjectWbsItem::class, 'project_id', 'project_id');
    }

    public function risks(): HasMany|self {
        return $this->hasMany(Risk::class, 'risk_project', 'project_id');
    }

    public function stakeholders(): HasManyThrough|self {
        return $this->hasManyThrough(
            InitiatingStakeholder::class,
            Initiating::class,
            'project_id',
            'initiating_id',
            'project_id',
            'initiating_id'
        );
    }

    public function acquisitions() {
        return $this->hasMany(AcquisitionPlanning::class, 'project_id', 'project_id');
    }


    public function communications() {
        return $this->hasMany(Communication::class, 'communication_project_id', 'project_id');
    }


    public function quality(): HasMany|self {
        return $this->hasMany(QualityPlanning::class, 'project_id', 'project_id');
    }
}
