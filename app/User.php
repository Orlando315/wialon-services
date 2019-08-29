<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombres', 'apellidos', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new ResetPassword($token));
    }

    public function checkRole($role)
    {
      return $this->role == $role;
    }

    public function isAdmin()
    {
      return $this->role == 'admin';
    }

    public function role()
    {
      return $this->role == 'admin' ? 'Administrador' : 'Usuario';
    }
}
