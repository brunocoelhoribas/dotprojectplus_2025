<?php

namespace App\Models\Monitoring;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringBaseline extends Model {
    protected $table = 'dotp_monitoring_baseline';
    public $timestamps = false;
    protected $primaryKey = 'baseline_id';

    protected $fillable = [
        'project_id',
        'baseline_name',
        'baseline_version',
        'baseline_observation',
        'user_id',
        'baseline_date',
    ];

    protected $casts = [
        'baseline_date' => 'date',
    ];

    public function tasks(): HasMany {
        return $this->hasMany(MonitoringBaselineTask::class, 'baseline_id', 'id');
    }
}
