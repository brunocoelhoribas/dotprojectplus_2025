<?php

namespace App\Models\Department;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para a tabela 'dotp_departments'
 */
class Department extends Model {
    use HasFactory;

    protected $table = 'dotp_departments';
    protected $primaryKey = 'dept_id';
    public $timestamps = false;

    protected $fillable = [
        'dept_parent',
        'dept_company',
        'dept_name',
        'dept_phone',
        'dept_fax',
        'dept_address1',
        'dept_address2',
        'dept_city',
        'dept_state',
        'dept_zip',
        'dept_url',
        'dept_desc',
        'dept_owner',
    ];

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'dept_company', 'company_id');
    }
}
