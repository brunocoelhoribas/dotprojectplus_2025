<?php

namespace App\Models\Planning\Cost\Budget;

use App\Models\Planning\Risk\Risk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetReserve extends Model {
    protected $table = 'dotp_budget_reserve';
    protected $primaryKey = 'budget_reserve_id';
    public $timestamps = false;

    protected $fillable = [
        'budget_reserve_project_id',
        'budget_reserve_risk_id',
        'budget_reserve_description',
        'budget_reserve_financial_impact',
        'budget_reserve_inicial_month',
        'budget_reserve_final_month',
        'budget_reserve_value_total'
    ];

    protected $casts = [
        'budget_reserve_inicial_month' => 'date',
        'budget_reserve_final_month' => 'date',
    ];

    public function risk(): BelongsTo {
        return $this->belongsTo(Risk::class, 'budget_reserve_risk_id', 'risk_id');
    }
}
