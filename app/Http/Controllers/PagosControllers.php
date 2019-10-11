<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Pago;
use App\Factura;
use Flow;

class PagosControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $facturas = Pago::facturas();
      $suscripciones = Pago::suscripciones();

      return view('pagos.index', compact('facturas', 'suscripciones'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
        //
    }

    /**
     * Registrar confirmacion de Pago de Flow
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function confirmation(Request $request)
    {
      if(!$request->token){
        abort(404);
      }

      // Buscar si el pago ya fue registrado
      $pago = Pago::where('token', $request->token)->first();

      $data = [
        'flash_class'   => 'alert-success',
        'flash_message' => 'Pago realizado exitosamente.',
      ];

      if(!$pago){
        $flow = new Flow;
        $response = $flow->send('payment/getStatus', ['token' => $request->token]);

        $factura = Factura::findOrFail($response->optional->factura);

        $pago = new Pago([
          'token' => $request->token,
          'flow_order' => $response->flowOrder,
          'request_date' => $response->requestDate,
          'status' => $response->status,
          'payer' => $response->payer,
          'response' => $response,
        ]);

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
          $factura->status = true;
        }else{
          $factura->status = false;

          $data = [
            'flash_class'   => 'alert-danger',
            'flash_message' => 'Ha ocurrido un error al realizar el pago.',
            'flash_important' => true,
          ];
        }

        $factura->pago()->save($pago);
        $factura->save();
      }

      if(Auth::check()){
        return redirect()->route('facturas.show', ['factura' => $response->optional->factura])->with($data);
      }
    }
}
