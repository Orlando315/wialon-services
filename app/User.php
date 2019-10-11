<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Flow;

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

    /**
     * Verificar si el User tiene el Role solicitado
     */
    public function checkRole($role)
    {
      return $this->role == $role;
    }

    /**
     * Verificar si el User tiene Role admin
     */
    public function isAdmin()
    {
      return $this->role == 'admin';
    }

    /**
     * Obtener el nombre del Role del User
     */
    public function role()
    {
      return $this->role == 'admin' ? 'Administrador' : 'Usuario';
    }

    /**
     * Obtener los Servicios del User
     */
    public function servicios()
    {
      return $this->hasMany('App\Servicio');
    }

    /**
     * Obtener los Repetidores del User
     */
    public function repetidores()
    {
      return $this->hasMany('App\Repetidor');
    }

    /**
     * Obtener los Logs del User
     */
    public function logs()
    {
      return $this->hasMany('App\Log');
    }

    /**
     * Obtener los Tokens del User
     */
    public function tokens()
    {
      return $this->hasMany('App\Token');
    }

    /**
     * Obtener las Facturas del User
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Registrar al User en Flow como un Customer
     */
    public function createFlowCustomer()
    {
      $flow = new Flow;

      $params = [
        'name' => $this->nombres.' '.$this->apellidos,
        'email' => $this->email,
        'externalId' => $this->id,
      ];

      $response = $flow->send('customer/create', $params, 'POST');

      if($response){
        $this->customerId = $response->customerId;
        return $this->save();
      }

      return false;
    }

    public function isCustomer()
    {
      return $this->customerId !== null;
    }

    /**
     * Obtener las Facturas pendientes de Pago
     */
    public function facturasPendientes(){
      return $this->facturas()->where('status', null);
    }

    /**
     * Obtener las Facturas pagadas
     */
    public function facturasPagadas(){
      return $this->facturas()->where('status', true);
    }

    /**
     * Obtener las Facturas rechazadas
     */
    public function facturasRechazadas(){
      return $this->facturas()->where('status', false);
    }

}
