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
}
