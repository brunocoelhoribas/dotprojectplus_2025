<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use HasFactory;

    protected $table = 'dotp_projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false;
}
