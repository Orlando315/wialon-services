<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\UserScope;

class Servicio extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      // Solo para Users con Role User
      if(Auth::check() && !Auth::user()->isAdmin()){
        static::addGlobalScope(new UserScope);
      }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'alias',
      'wialon',
    ];

    protected $cast = [
      'active' => 'boolean'
    ];

    /**
     * Obtener el User al que pertenece el Servicio
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener los Repetidores registrados en el Servicio
     */
    public function repetidores()
    {
      return $this->hasMany('App\Repetidor');
    }

    /**
     * Eliminar token de Wialon del Servicio
     */
    public function disableToken()
    {
      $this->wialon = null;
      $this->save();
    }
}
