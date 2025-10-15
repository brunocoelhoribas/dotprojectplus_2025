<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use Notifiable;

    protected $table = 'dotp_users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'user_username',
        'user_password',
    ];


    public function getAuthPassword(): string {
        return $this->user_password;
    }
}
