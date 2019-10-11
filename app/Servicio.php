<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Notifications\WialonTokenExpiration;
use App\Notifications\WialonTokenDeleted;
use App\Scopes\UserScope;

class Servicio extends Model
{
    use Notifiable;

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
      'wialon_expiration',
    ];

    protected $cast = [
      'active' => 'boolean'
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
      return $this->user->email;
    }

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
     * Obtener los Logs del Servicio
     */
    public function logs()
    {
      return $this->hasMany('App\Log');
    }

    /**
     * Obtener los Tokens del Servicio
     */
    public function tokens()
    {
      return $this->hasMany('App\Token');
    }

    /**
     * Obtener los Logs solo del Servicio. Sin los repetidores
     */
    public function logsAll()
    {
      return $this->logs()
                  ->where('repetidor_id', null)
                  ->latest();;
    }

    /**
     * Obtener los Logs exitosos del Servicio
     */
    public function logsSuccess()
    {
      return $this->logs()
                  ->where([
                    ['repetidor_id', null],
                    ['error', false]
                  ])
                  ->latest();
    }

    /**
     * Obtener los Logs de errores del Servicio
     */
    public function logsError()
    {
      return $this->logs()
                  ->where([
                    ['repetidor_id', null],
                    ['error', true]
                  ])
                  ->latest();
    }

    /**
     * Formatear logs para las tablas en la vista de Servicio
     * @param  String  $type
     */
    public function formatLogs($type)
    {
      $logs = [];
      foreach ($this->{$type}()->get() as $log) {
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
     * Eliminar token de Wialon del Servicio
     */
    public function disableToken()
    {
      $this->wialon = null;
      $this->save();

      $this->sendWialonTokenDeletedEmail();
    }

    /**
     * Notificar al User que el Token de Wialon esta por expirar
     */
    public function sendWialonTokenExpirationEmail()
    {
      $nombre = $this->user->nombres.' '.$this->user->apellidos;

      $this->notify(new WialonTokenExpiration($nombre, $this));
    }

    /**
     * Notificar al User que el Token de Wialon fue eliminado
     */
    public function sendWialonTokenDeletedEmail()
    {
      $nombre = $this->user->nombres.' '.$this->user->apellidos;

      $this->notify(new WialonTokenDeleted($nombre, $this));
    }

    /**
     * Actualizar la fecha de expiracion del Token
     */
    public function updateExpiration()
    {
      $this->wialon_expiration = $this->updated_at->addDays('30');
      $this->save();
    }

    /**
     * Verificar si el Token de Wialon esta por expirar (Dentro de 5 dias o menos)
     */
    public function isAboutToExpire()
    {
      return $this->wialon_expiration <= date('Y-m-d H:i:s', strtotime('+5 days'));
    }

    /**
     * Obtener el estado del Servicio
     */
    public function status()
    {
      return $this->active ?  '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
    }

    /**
     * Obtener las Suscriciones del Servicio
     */
    public function suscripciones()
    {
      return $this->hasMany('App\Suscripcion');
    }

    /**
     * Obtener los Planes a los que esta Suscrito el Servicio
     */
    public function planes()
    {
      return $this->belongsToMany('App\Plan', 'suscripciones')
                  ->using('App\Suscripcion')
                  ->withPivot(
                    'status',
                    'subscriptionId',
                    'subscription_start',
                    'period_start',
                    'period_end',
                    'status_flow',
                    'response'
                  );
    }

    /**
     * Devolver el Alias del servicio. Si no esta definido, devolver el id
     */
    public function alias()
    {
      return $this->alias ?? 'Servicio #'.$this->id;
    }

    /**
     * Obtener la suscripcion Activa del Servicio
     */
    public function activeSuscripcion()
    {
      return $this->hasOne('App\Suscripcion')->where('status', true);
    }

    /**
     * Obtener la suscripcion Pendiente del Servicio
     */
    public function pendingSuscripcion()
    {
      return $this->hasOne('App\Suscripcion')->where('status', null);
    }

    /**
     * Obtener la ultima Suscricione del Servicio
     */
    public function lastSuscripcion()
    {
      return $this->hasMany('App\Suscripcion')->latest()->first();
    }
}
