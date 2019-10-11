<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuscripcionInvoice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suscripciones_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'invoiceId',
      'amount',
      'attemp_count',
      'attemped',
      'next_attemp_date',
      'due_date',
      'status',
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
     * Obtener los Pagos del Invoice
     */
    public function pagos(){
      return $this->hasMany('App\Pago', 'invoice_id');
    }

    /**
     * Obtener la Suscripcion del Invoice
     */
    public function suscripcion(){
      return $this->belongsTo('App\Suscripcion');
    }

    /**
     * Obtener el Plan del Invoice
     */
    public function plan(){
      return $this->suscripcion->plan;
    }
}
