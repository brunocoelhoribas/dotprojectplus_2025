<?php

namespace App\Models\Planning\Cost\Budget;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model {
    protected $table = 'dotp_budget';
    protected $primaryKey = 'budget_id';
    public $timestamps = false;

    protected $fillable = [
        'budget_project_id',
        'budget_reserve_management',
        'budget_sub_total',
        'budget_total'
    ];
}
