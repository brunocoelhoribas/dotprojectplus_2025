<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model {
    use HasFactory;

    protected $table = 'dotp_contacts';
    protected $primaryKey = 'contact_id';
    public $timestamps = false;

    public function getFullNameAttribute(): string {
        return $this->contact_first_name . ' ' . $this->contact_last_name;
    }
}
