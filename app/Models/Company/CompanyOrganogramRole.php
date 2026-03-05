<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyOrganogramRole extends Model {
    protected $table = 'dotp_company_role';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'sort_order',
        'role_name',
        'identation'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'identation' => 'integer',
    ];
}
