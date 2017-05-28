<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_PARTNER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'callback_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Default value for attributes
     *
     * @var array
     */
    protected $attributes = [
        'role_id' => self::ROLE_PARTNER
    ];

    /**
     * Get the user role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin() {
        return ( $this->role->id == self::ROLE_ADMIN ) ? true : false;
    }
}
