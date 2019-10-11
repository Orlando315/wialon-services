<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Suscripcion;
use App\Plan;
use App\Pago;
use App\Servicio;
use App\SuscripcionInvoice;
use Flow;

class SuscripcionesControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Cancelar la Susceripcion activa del Servicio
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, Servicio $servicio)
    {
      $this->validate($request, [
        'cancel' => 'required|in:0,1'
      ]);

      if($servicio->activeSuscripcion){
        if($servicio->activeSuscripcion->cancelFlowSubscription($request->cancel)){
          return response()->json(['end' => $servicio->activeSuscripcion->period_end,'status' => $servicio->activeSuscripcion->status()]);
        }
      }

      return response()->json(false);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function planes(Servicio $servicio = null)
    {
      $planes = Plan::where('deleted_at', null)->get();

      return view('suscripciones.planes', compact('planes', 'servicio'));
    }

    /**
     * Suscribir al User a un Plan.
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Plan $plan, Servicio $servicio = null)
    {
      if($plan->deleted_at){
        abort(404);
      }

      if(!Auth::user()->isCustomer()){
        Auth::user()->createFlowCustomer();
      }

      if($servicio && $servicio->pendingSuscripcion){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
            'flash_message' => 'El servicio tiene una suscripción pendiente de pago.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
            ]);
      }

      if($servicio && $servicio->activeSuscripcion){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
            'flash_message' => 'El servicio ya tiene una suscripción activa.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
            ]);
      }

      $suscripcion = new Suscripcion;

      if($suscripcion->createFlowSubscription($plan, $servicio)){
        return redirect()->route('servicios.show', ['servicio' => $suscripcion->servicio_id])->with([
          'flash_message' => 'Plan agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->route('suscripciones.planes')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
    }

    /**
     * Registrar confirmacion de Invoice de Flow
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmation(Request $request)
    {
      if(!$request->token){
        abort(404);
      }

      $flow = new Flow;
      $response = $flow->send('payment/getStatus', ['token' => $request->token]);

      $pago = new Pago([
        'token' => $request->token,
        'flow_order' => $response->flowOrder,
        'request_date' => $response->requestDate,
        'status' => $response->status,
        'payer' => $response->payer,
        'response' => $response,
      ]);

      $orders = $this->splitCommerceOrder($response->commerceOrder);

      $suscripcion = Suscripcion::where('subscriptionId', $orders->suscripcion)->first();
      $invoice = SuscripcionInvoice::where('invoiceId', $orders->invoice)->first();

      if($response && in_array($response->status, [1, 2])){
        $pago->fill([
          'payment_date' => $response->paymentData->date,
          'medio' => $response->paymentData->media,
          'amount' => $response->paymentData->amount,
          'fee' => $response->paymentData->fee,
          'taxes' => $response->paymentData->taxes,
          'balance' => $response->paymentData->balance,
          'transfer_date' => $response->paymentData->transferDate,
        ]);

        $suscripcion->status = true;
      }

      $invoice->pago()->save($pago);
    }

    public function splitCommerceOrder($order)
    {
      $split = explode('_', $order);
      $orders = [
        'suscripcion' => $split[0].'_'.$split[1],
        'invoice' => $split[2],
        'date' => $split[3]
      ];

      return (object)$orders;
    }
}
