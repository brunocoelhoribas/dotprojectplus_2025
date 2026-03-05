<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanResourcesRole extends Model {
    protected $table = 'dotp_human_resources_role';
    protected $primaryKey = 'human_resources_role_id';
    public $timestamps = false;

    protected $fillable = [
        'human_resources_role_company_id',
        'human_resources_role_name',
        'human_resources_role_responsability',
        'human_resources_role_authority',
        'human_resources_role_competence',
    ];

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'human_resources_role_company_id', 'company_id');
    }
}
