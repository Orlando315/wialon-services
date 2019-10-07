<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Flow;

class Factura extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facturas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id',
      'servicio_id',
      'descripcion',
      'monto'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'response' => 'array'
    ];

    /**
     * Obtener el User al que pertenece el Servicio
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /*
     * Obtener el status de la Factura
     */
    public function status()
    {
      if($this->status === null){
        return '<span class="badge badge-secondary">Pendiente</span>';  
      }

      if($this->status == 1){
        return '<span class="badge badge-success">Pagada</span>';  
      }

      return '<span class="badge badge-danger">Rechazada</span>';
    }

    /*
     * Verificar si la factura tiene un Servicio
     */
    public function hasServicio()
    {
      return $this->servicio_id != null;
    }

    /*
     * Verificar si la factura tiene un Servicio
     */
    public function monto()
    {
      return number_format($this->monto, 0, ',', '.');
    }

    /**
     * Generar commerceOrder para Flow
     */
    public function generateCommerceOrder()
    {
      $this->commerceOrder = str_random(15);
    }

    /*
     * Crear Payment en Flow
     */
    public function createFlowPayment()
    {
      $flow = new Flow;

      $params = [
        'commerceOrder' => $this->commerceOrder,
        'subject' => $this->descripcion,
        'currency' => 'CLP',
        'amount' => $this->monto,
        'email' => $this->user->email,
        'paymentMethod' => 9,
        'urlConfirmation' => route('pagos.confirmation'),
        'urlReturn' => route('pagos.confirmation'),
        'optional' => json_encode(['factura' => $this->id]),
      ];

      $response = $flow->send('payment/create', $params, 'POST');

      if($response){
        $this->flow_token = $response->token;
        $this->flow_order = $response->flowOrder;
        $this->flow_url = $response->url;
        $this->response = $response;
        return $this->save();
      }

      return false;
    }

    /*
     * Crear la url para realizar el pago
     */
    public function pagoUrl()
    {
      if($this->flow_url && $this->flow_token){
        return $this->flow_url . '?token=' . $this->flow_token;
      }else if($this->createFlowPayment()){
        return $this->pagoUrl();
      }

      return '#';
    }

    /**
     * Obtener el Pago de la Factura
     */
    public function pago()
    {
      return $this->HasOne('App\Pago');
    }

    /**
     * Obtener las Facturas pendientes de Pago
     */
    public static function pendientes(){
      return Factura::where('status', null)->get();
    }

    /**
     * Obtener las Facturas pagadas
     */
    public static function pagadas(){
      return Factura::where('status', true)->get();
    }

    /**
     * Obtener las Facturas rechazadas
     */
    public static function rechazadas(){
      return Factura::where('status', false)->get();
    }
}
