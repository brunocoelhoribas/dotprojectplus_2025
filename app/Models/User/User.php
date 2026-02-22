<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\HumanResource\HumanResource;
use App\Models\Planning\Cost\Cost;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Added import

/**
 * Eloquent model representing a user in the 'dotp_users' table.
 *
 * This model is authenticatable and manages user data and their
 * relationship to contact information.
 *
 * @property int $user_id Primary key.
 * @property string $user_username The user's login name.
 * @property string $user_password The user's hashed password.
 * @property int $user_contact Foreign key to the user_contacts table (contact_id).
 *
 * @property-read UserContact|null $contact The associated contact details for this user.
 */
class User extends Authenticatable {
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Removed duplicate 'use Notifiable;'

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dotp_users';

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_username',
        'user_password',
        'user_contact',
        'user_parent',
        'user_type',
        'user_company',
        'user_department',
        'user_owner',
        'user_signature'
    ];

    /**
     * Overrides the default method to retrieve the user's password for authentication.
     *
     * @return string The user's hashed password.
     */
    public function getAuthPassword(): string {
        return $this->user_password;
    }

    /**
     * Defines the HasOne relationship with the UserContact model.
     *
     * Note: This assumes 'user_contact' on the 'dotp_users' table
     * holds the 'contact_id' from the 'dotp_user_contacts' table.
     *
     * @return HasOne
     */
    public function contact(): HasOne {
        // The foreign key is 'user_contact' on this (User) model,
        // and the local key (owner key) is 'contact_id' on the UserContact model.
        return $this->hasOne(UserContact::class, 'contact_id', 'user_contact');
    }

    public function costs(): HasMany|self {
        return $this->hasMany(Cost::class, 'cost_human_resource_id', 'user_id')
            ->orderBy('cost_date_begin', 'desc');
    }

    public function humanResource(): HasOne|self {
        return $this->hasOne(HumanResource::class, 'human_resource_user_id', 'user_id');
    }
}
