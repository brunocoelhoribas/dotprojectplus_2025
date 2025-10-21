<?php

namespace App\Models\Company;

use App\Models\Project;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model {
    use HasFactory;

    protected $table = 'dotp_companies';
    protected $primaryKey = 'company_id';
    public $timestamps = false;
    protected $fillable = [
        'company_module',
        'company_name',
        'company_phone1',
        'company_phone2',
        'company_fax',
        'company_address1',
        'company_address2',
        'company_city',
        'company_state',
        'company_zip',
        'company_primary_url',
        'company_owner',
        'company_description',
        'company_type',
        'company_email',
        'company_custom',
    ];


    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'company_owner', 'user_id');
    }

    public function policies(): HasOne {
        return $this->hasOne(CompanyPolicy::class, 'company_policies_company_id', 'company_id');
    }

    public function projects() {
        return $this->hasMany(Project::class, 'project_company', 'company_id');
    }
}
