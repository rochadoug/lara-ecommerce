<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Admin() {
        //public function ClienteAdmin() {
            ///if ( auth()->user()->admin )
            return $this->hasOne('App\Admin', 'id_usuario', 'id');
    }

    public function Cliente() {
        //else
            return $this->hasOne('App\Cliente', 'id_usuario', 'id');
    }

    /**
     * Check if user is an admin or not.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }
}
