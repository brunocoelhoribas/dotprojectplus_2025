<?php

namespace App\Models\Planning\Cost;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model {
    protected $table = 'dotp_costs';
    protected $primaryKey = 'cost_id';
    public $timestamps = false;

    protected $fillable = [
        'cost_type_id',
        'cost_project_id',
        'cost_description',
        'cost_quantity',
        'cost_date_begin',
        'cost_date_end',
        'cost_value_unitary',
        'cost_value_total',
        'cost_human_resource_id',
        'cost_human_resource_role_id'
    ];

    protected $casts = [
        'cost_date_begin' => 'datetime',
        'cost_date_end' => 'datetime',
        'cost_value_unitary' => 'decimal:2',
        'cost_value_total' => 'decimal:2',
    ];

    public function humanResource(): BelongsTo {
        return $this->belongsTo(User::class, 'cost_human_resource_id', 'user_id');
    }
}
