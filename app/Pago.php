<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'token',
      'flow_order',
      'request_date',
      'status',
      'payer',
      'payment_date',
      'medio',
      'amount',
      'fee',
      'taxes',
      'balance',
      'transfer_date',
      'response',
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

    /**
     * Obtener la Facurta a la que pertenece el Pago
     */
    public function factura()
    {
      return $this->belongsTo('App\Factura');
    }

    /**
     * Obtener el Invoice al que pertenece el pago
     */
    public function invoice()
    {
      return $this->belongsTo('App\SuscripcionInvoice', 'invoice_id');
    }

    /**
     * Obtener el Servicio al que pertenece el Pago
     */
    public function servicio()
    {
      return $this->invoice->suscripcion->servicio;
    }

    /**
     * Obtener el Plan al que pertenece el Pago
     */
    public function plan()
    {
      return $this->invoice->suscripcion->plan;
    }

    /*
     * Obtener el status de la Factura
     */
    public function status()
    {
      if($this->status === null){
        return '<span class="badge badge-success">Pendiente</span>';  
      }

      if($this->isCompleto()){
        return '<span class="badge badge-success">Completo</span>';
      }

      return '<span class="badge badge-danger">Rechazado</span>';
    }

    public function isCompleto()
    {
      return $this->status == 1 || $this->status == 2;
    }

    public function amount()
    {
      return number_format($this->amount, 0, ',', '.');
    }

    public function fee()
    {
      return number_format($this->fee, 0, ',', '.');
    }

    public function taxes()
    {
      return number_format($this->taxes, 0, ',', '.');
    }

    public function balance()
    {
      return number_format($this->balance, 0, ',', '.');
    }

    /**
     * Obtener los Pagos de Facturas
     */
    public static function facturas(){
      return Pago::where('invoice_id', null)->get();
    }

    /**
     * Obtener los Pagos de Suscripciones
     */
    public static function suscripciones(){
      return Pago::where('factura_id', null)->get();
    }
}
