<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Eloquent model representing company policies in the 'dotp_company_policies' table.
 *
 * This model stores policy-related information for a specific company.
 *
 * @property int $company_policies_id Primary key.
 * @property int $company_policies_company_id Foreign key linking to the company (company_id).
 * @property string|null $company_policies_recognition Text or data related to recognition policies.
 * @property string|null $company_policies_policy Text or data related to general policies.
 * @property string|null $company_policies_safety Text or data related to safety policies.
 *
 * @property-read Company $company The company this policy belongs to.
 */
class CompanyPolicy extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dotp_company_policies';

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'company_policies_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_policies_company_id',
        'company_policies_recognition',
        'company_policies_policy',
        'company_policies_safety',
    ];

    /**
     * Defines the BelongsTo relationship with the Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_policies_company_id', 'company_id');
    }
}
