<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Eloquent model representing user contact details in the 'dotp_contacts' table.
 *
 * @property int $contact_id Primary key.
 * @property string|null $contact_first_name User's first name.
 * @property string|null $contact_last_name User's last name.
 * @property string|null $contact_email User's email address.
 * @property string|null $contact_phone User's phone number.
 * @property string|null $contact_address1 Address (line 1).
 * @property string|null $contact_city City.
 * @property string|null $contact_state State.
 * @property string|null $contact_zip Zip code (Postal Code).
 *
 * @property-read string $full_name Accessor for the user's full name.
 * @property-read User|null $user The user associated with this contact.
 */
class UserContact extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dotp_contacts';

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'contact_id';

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
        'contact_first_name',
        'contact_last_name',
        'contact_title',
        'contact_birthday',
        'contact_company',
        'contact_email',
        'contact_phone',
        'contact_address1',
        'contact_address2',
        'contact_city',
        'contact_state',
        'contact_zip',
    ];

    /**
     * Accessor to get the user's full name by combining
     * first and last names.
     *
     * @return string
     */
    public function getFullNameAttribute(): string {
        return $this->contact_first_name . ' ' . $this->contact_last_name;
    }

    /**
     * Defines the inverse HasOne relationship with the User model.
     *
     * This defines the other side of the 1-to-1 relationship,
     * based on the 'user_contact' key on the 'dotp_users' table.
     *
     * @return HasMany|UserContact
     */
    public function users(): self|HasMany {
        return $this->hasMany(
            User::class,
            'user_company',
            'company_id'
        );
    }
}
