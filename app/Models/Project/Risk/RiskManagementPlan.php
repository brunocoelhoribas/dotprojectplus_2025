<?php

namespace App\Models\Project\Risk;

use Illuminate\Database\Eloquent\Model;

class RiskManagementPlan extends Model {
    protected $table = 'dotp_risks_management_plan';
    protected $primaryKey = 'risk_plan_id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'probability_super_low', 'probability_low', 'probability_medium', 'probability_high', 'probability_super_high',
        'impact_super_low', 'impact_low', 'impact_medium', 'impact_high', 'impact_super_high',

        'matrix_superlow_superlow', 'matrix_superlow_low', 'matrix_superlow_medium', 'matrix_superlow_high', 'matrix_superlow_superhigh',
        'matrix_low_superlow', 'matrix_low_low', 'matrix_low_medium', 'matrix_low_high', 'matrix_low_superhigh',
        'matrix_medium_superlow', 'matrix_medium_low', 'matrix_medium_medium', 'matrix_medium_high', 'matrix_medium_superhigh',
        'matrix_high_superlow', 'matrix_high_low', 'matrix_high_medium', 'matrix_high_high', 'matrix_high_superhigh',
        'matrix_superhigh_superlow', 'matrix_superhigh_low', 'matrix_superhigh_medium', 'matrix_superhigh_high', 'matrix_superhigh_superhigh',

        'risk_contengency_reserve_protocol',
        'risk_revision_frequency'
    ];
}
