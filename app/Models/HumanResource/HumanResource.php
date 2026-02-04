<?php

namespace App\Models\HumanResource;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanResource extends Model {
    protected $table = 'dotp_human_resource';
    protected $primaryKey = 'human_resource_id';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'human_resource_user_id', 'user_id');
    }
}
