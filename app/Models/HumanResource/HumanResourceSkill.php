<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Model;

class HumanResourceSkill extends Model{
    protected $table = 'dotp_skills';
    protected $primaryKey = 'skill_id';

    protected $fillable = ['skill_name', 'skill_type'];
}
