<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified', 'phone', 'phone_country_code', 'phone_verified', 'dob', 'gender', 'tnc_accepted', 'password', 'set_password_now', 'entity_id', 'gplus_data'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_access_token', 'api_refresh_token', 'tnc_accepted'
    ];

    protected $casts = [
        'gplus_data' => 'json',
    ];

    public function getProfilePictureAttribute() {
        return $this->attributes['picture'];
    }

    public function entity() {
        return $this->hasOne('App\Models\Entity','id','entity_id');
    }
}
