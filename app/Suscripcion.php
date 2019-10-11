<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log as LaravelLog;
use App\Servicio;
use Flow;

class Suscripcion extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suscripciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'servicio_id',
      'status',
      'subscriptionId',
      'subscription_start',
      'period_start',
      'period_end',
      'status_flow',
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
     * Obtener el Plan de la Suscripcion
     */
    public function plan()
    {
      return $this->belongsTo('App\Plan');
    }

    /**
     * Obtener el Servicio de la Suscripcion
     */
    public function servicio()
    {
      return $this->belongsTo('App\Servicio');
    }

    /**
     * Obtener los Invoices de la Suscripcion
     */
    public function invoices()
    {
      return $this->hasMany('App\SuscripcionInvoice', 'suscripcion_id');
    }

    /**
     * Obtener el estado de la Suscripcion
     */
    public function status()
    {
      if($this->status === null){
        return '<span class="badge badge-secondary">Pendiente</span>';
      }

      return $this->status ?  '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
    }

    /**
     * Crear un Servicio para el User
     */
    protected function createServicio()
    {
      return Auth::user()->servicios()->create([]);
    }

    /**
     * Registrar los Invoice de Flow
     *
     * @param  Array  $invoices
     * @return \Illuminate\Http\Response
     */
    protected function storeInvoices($invoices)
    {
      foreach ($invoices as $invoice) {
        $this->invoices()->create([
          'invoiceId' => $invoice->id,
          'amount' => $invoice->amount,
          'attemp_count' => $invoice->attemp_count,
          'attemped' => $invoice->attemped,
          'next_attemp_date' => $invoice->next_attemp_date,
          'due_date' => $invoice->due_date,
          'status' => $invoice->status,
          'response' => $invoice,
        ]);
      }
    }

    /**
     * Suscribir al User al Plan en Flow
     *
     * @param  \App\Plan $plan
     * @param  \App\Servicio $servicio
     * @return Bool
     */
    public function createFlowSubscription($plan, $servicio)
    {
      $flow = new Flow;

      $params = [
        'planId' => $plan->planId,
        'customerId' => Auth::user()->customerId,
      ];

      $response = $flow->send('subscription/create', $params, 'POST');
      if($response){
        $servicio = $servicio ?? $this->createServicio();

        $this->servicio_id = $servicio->id;
        $this->subscriptionId = $response->subscriptionId;
        $this->subscription_start = $response->subscription_start;
        $this->period_start = $response->period_start;
        $this->period_end = $response->period_end;
        $this->status_flow = $response->status;
        $this->response = $response;

        $plan->suscripciones()->save($this);
        $this->storeInvoices($response->invoices);

        return true;
      }

      return false;
    }

    /**
     * Cancelar la Suscripcion activa de un Servicio
     *
     * @param  int  $endType
     * @return Bool
     */
    public function cancelFlowSubscription($endType)
    {
      $flow = new Flow;

      $params = [
        'subscriptionId' => $this->subscriptionId,
        'at_period_end' => $endType,
      ];

      $response = $flow->send('subscription/cancel', $params, 'POST');

      if($response){
        LaravelLog::channel('flow')->info('User: '.Auth::id().' | Cancelar Suscripcion: '.$this->id.', '.$this->subscriptionId, ['response' => $response]);

        $this->status_flow = $response->status;
        $this->period_end = $response->period_end;
        $this->cancel_at = $response->cancel_at;
        $this->cancel_type = $endType;
        $this->response = $response;
        $this->status = $endType == 1;

        return $this->save();
      }

      return false;
    }
}
