<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\UserScope;

class Token extends Model
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
    protected $fillable = [];

    /**
     * Obtener el User al que pertenece el Servicio
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener el Servicio al que pertenece el Repetidor
     */
    public function servicio()
    {
      return $this->belongsTo('App\Servicio');
    }

    public function generateToken()
    {
      $this->token = str_random(50);
    }
}
