<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyPolicy extends Model {
    protected $table = 'dotp_company_policies';
    protected $primaryKey = 'company_policies_id';
    public $timestamps = false;
}
