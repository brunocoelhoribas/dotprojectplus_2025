<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyPolicy extends Model {
    protected $table = 'dotp_company_policies';
    protected $primaryKey = 'company_policies_id';
    public $timestamps = false;

    protected $fillable = [
        'company_policies_company_id',
        'company_policies_recognition',
        'company_policies_policy',
        'company_policies_safety',
    ];
}
