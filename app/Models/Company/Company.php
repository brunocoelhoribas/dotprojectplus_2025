<?php

namespace App\Models\Company;

use App\Models\Project;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Adicionado para o tipo de retorno
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Eloquent model representing a company in the 'dotp_companies' table.
 *
 * This model manages company data, including its relationships
 * with users (owner), policies, and projects.
 *
 * @property int $company_id Primary key of the table.
 * @property int $company_module Associated module ID (if applicable).
 * @property string $company_name The company's name.
 * @property string|null $company_phone1 Primary phone.
 * @property string|null $company_phone2 Secondary phone.
 * @property string|null $company_fax Fax number.
 * @property string|null $company_address1 Address (line 1).
 * @property string|null $company_address2 Address (line 2).
 * @property string|null $company_city City.
 * @property string|null $company_state State.
 * @property string|null $company_zip Zip code (Postal Code).
 * @property string|null $company_primary_url Primary URL (website) of the company.
 * @property int $company_owner Foreign key for the owner user (user_id).
 * @property string|null $company_description Long description of the company.
 * @property int|null $company_type Company type (e.g., 0=Client, 1=Supplier).
 * @property string|null $company_email Primary contact email.
 * @property string|null $company_custom Custom field (usually JSON or text).
 *
 * @property-read User $owner The user (owner) associated with this company.
 * @property-read CompanyPolicy|null $policy The associated company policy (if it exists).
 * @property-read \Illuminate\Database\Eloquent\Collection|Project[] $projects The collection of projects for this company.
 */
class Company extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dotp_companies';

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'company_id';

    /**
     * Indicates if the model should be timestamped (created_at and updated_at).
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

    /**
     * Defines the BelongsTo relationship with the owner user.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'company_owner', 'user_id');
    }

    /**
     * Defines the HasOne relationship with the company's policy.
     *
     * @return HasOne
     */
    public function policy(): HasOne {
        return $this->hasOne(
            CompanyPolicy::class,
            'company_policies_company_id',
            'company_id'
        );
    }

    /**
     * Defines the HasMany relationship with the projects.
     *
     * @return HasMany
     */
    public function projects(): HasMany {
        return $this->hasMany(Project::class, 'project_company', 'company_id');
    }
}

