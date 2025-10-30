<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model {
    use HasFactory;

    protected $table = 'dotp_projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false;

    /**
     * Defines the BelongsTo relationship with the owner user.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'project_owner', 'user_id');
    }
}
