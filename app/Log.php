<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\UserScope;

class Log extends Model
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
      'user_id',
      'servicio_id',
      'repetidor_id',
      'error',
      'code',
      'token',
      'message',
      'result'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'error' => 'boolean',
        'result' => 'array'
    ];


    public function type()
    {
      return $this->error ? '<span class="badge badge-danger">Sí</span>' : '<span class="badge badge-secondary">No</span>';
    }
}
