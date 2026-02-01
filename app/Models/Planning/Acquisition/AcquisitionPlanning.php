<?php

namespace App\Models\Planning\Acquisition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcquisitionPlanning extends Model {
    protected $table = 'dotp_acquisition_planning';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'items_to_be_acquired',
        'contract_type',
        'documents_to_acquisition',
        'supplier_management_process'
    ];

    public function criteria(): HasMany {
        return $this->hasMany(AcquisitionCriteria::class, 'acquisition_id', 'id');
    }

    public function requirements(): HasMany {
        return $this->hasMany(AcquisitionRequirement::class, 'acquisition_id', 'id');
    }

    public function roles(): HasMany {
        return $this->hasMany(AcquisitionRole::class, 'acquisition_id', 'id');
    }
}
