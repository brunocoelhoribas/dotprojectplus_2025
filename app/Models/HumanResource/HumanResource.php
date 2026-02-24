<?php

namespace App\Models\HumanResource;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static findOrFail($humanResourceId)
 */
class HumanResource extends Model {
    protected $table = 'dotp_human_resource';
    protected $primaryKey = 'human_resource_id';
    public $timestamps = false;
    protected $fillable = [
        'human_resource_user_id',
        'human_resource_lattes_url',
        'human_resource_sun',
        'human_resource_mon',
        'human_resource_tue',
        'human_resource_wed',
        'human_resource_thu',
        'human_resource_fri',
        'human_resource_sat'
    ];

    public function getCanDeleteAttribute() {
        return true;
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'human_resource_user_id', 'user_id');
    }

    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            HumanResourcesRole::class,
            'dotp_human_resource_roles',
            'human_resource_id',
            'human_resources_role_id'
        );
    }

    public function skills(): BelongsToMany {
        return $this->belongsToMany(
            HumanResourceSkill::class,
            'dotp_human_resource_skills',
            'human_resource_id',
            'skill_id'
        )->withPivot('proficiency_level');
    }
}
