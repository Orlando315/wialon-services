<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\UserScope;

class Repetidor extends Model
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

    protected $table = 'repetidores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'alias',
      'token',
      'endpoint',
    ];

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

    /**
     * Obtener los Logs del Repetidor
     */
    public function logs()
    {
      return $this->hasMany('App\Log');
    }

    /**
     * Obtener los Logs solo del Servicio. Sin los repetidores
     */
    public function logsAll()
    {
      return $this->logs()
                  ->latest();;
    }

    /**
     * Obtener los Logs exitosos del Servicio
     */
    public function logsSuccess()
    {
      return $this->logs()
                  ->where('error', false)
                  ->latest();
    }

    /**
     * Obtener los Logs de errores del Servicio
     */
    public function logsError()
    {
      return $this->logs()
                  ->where('error', true)
                  ->latest();
    }

    /**
     * Formatear logs para las tablas en la vista de Servicio
     * @param  String  $type
     */
    public function formatLogs($type)
    {
      $logs = [];
      foreach ($this->{$type} as $log) {
        $group = [];
        $group['date'] = $log->created_at->format('Y-m-d H:i:s');
        $group['message'] = $log->message ?? '';
        $group['token'] = $log->token ?? '';

        if($type == 'logsAll'){
          $group['error'] = $log->type();
        }

        if($type == 'logsError' || $type == 'logsAll'){
          $group['code'] = $log->code ?? '';
        }

        $logs[] = $group;
      }

      return $logs;
    }

    /**
     * Obtener el estado del ultimo Log del Repetidor
     */
    public function lastStatus()
    {
      $last = $this->logs->last();

      return $last ? $last->error ? '<span class="badge badge-danger">Error</span>' : '<span class="badge badge-success">Ok</span>' : '-';
    }

    /**
     * Obtener el mensaje del ultimo Log del Repetidor
     */
    public function lastMessage()
    {
      $last = $this->logs->last();
      return $last ? $last->message : '';
    }

    /**
     * Eliminar el token del Repetidor
     */
    public function disableToken()
    {
      $this->token = null;
      $this->save();
    }
}
