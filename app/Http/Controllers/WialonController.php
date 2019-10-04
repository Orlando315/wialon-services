<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;
use App\Servicio;
use Wialon;
use App\Log;
use App\Token;

class WialonController extends Controller
{ 
    protected function getInfo($servicio)
    {
      $wialon_api = new Wialon();

      $result = json_decode($wialon_api->login($servicio->wialon), true);

      if(!isset($result['error'])){
        $units = json_decode($wialon_api->core_update_data_flags('{"spec":[{"type":"type","data":"avl_unit","flags":1025,"mode":0}]}'));
        $wialon_api->logout();

        return $units;
      }else{

        // Guardamos el error
        Log::create([
          'user_id' => $servicio->user_id,
          'servicio_id' => $servicio->id,
          'code' => $result['error'],
          'token' => $servicio->wialon,
          'message' => $result['reason'],
          'result' => $result
        ]);

        // Si es un error de login, eliminar el token
        if(in_array($result['error'], [1, 4, 7, 8])){
          LaravelLog::channel('tokens')->info('Desactivar token de Wialon al usuario: '.$servicio->user_id, ['error' => $result['error']]);
          $servicio->disableToken();
        }
      }
    }

    protected function sendData($units, $servicio)
    {
      $repetidores = $servicio->repetidores()->whereNotNull('token')->get();

      foreach ($units as $unit) {
        $name = strlen($unit->d->nm) <= 10 ? $unit->d->nm : substr($unit->d->nm, 0, 10);

        $info = [
          'patente' => $name,
          'fecha_hora' => gmdate('Y-m-d H:i:s', $unit->d->pos->t ?? time()),
          'latitud' => $unit->d->pos->y ?? 0,
          'longitud' => $unit->d->pos->x ?? 0,
          'direccion' => 100,
          'velocidad' => $unit->d->pos->s ?? 0,
          'estado_registro' => 1,
          'estado_ignicion' => 1,
          'numero_evento' => 45
        ];

        $data = json_encode(['posicion' => $info]);

        foreach ($repetidores as $repetidor) {
          $curl = curl_init('http://ei.wisetrack.cl/API/Centinela/' . $repetidor->endpoint);

          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization: Bearer ' . $repetidor->token]);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

          $result = json_decode(curl_exec($curl), true);

          // Creamos el log
          $log = new Log([
            'user_id' => $servicio->user_id,
            'servicio_id' => $servicio->id,
            'token' => $repetidor->token,
            'result' => $result
          ]);

          if(isset($result['fault']) && $result['fault']['code'] == 900901){
            $log->code = $result['fault']['code'];
            $log->message = 'Desactivar tokens';

            LaravelLog::channel('tokens')->info('Desactivar token de Wisetrack al usuario: ' . $servicio->user_id, $result['fault']);
            $repetidor->disableToken();
          }else{
            if(isset($result['Exception'])){
              $log->code = 0;
              $log->message = $result['Exception'];
            }else{
              $log->error = in_array($result['RespuestaServicioWeb']['RespuestaOperacion']['ResultadoTransaccion']['Estado'], [2, 3, 4, 5, 6]);
              $log->code = $result['RespuestaServicioWeb']['RespuestaOperacion']['ResultadoTransaccion']['Estado'];
              $log->message = $result['RespuestaServicioWeb']['RespuestaOperacion']['ResultadoTransaccion']['DetalleEjecucion'];
            }
          }

          // Guardar el log
          $log->save();
          curl_close($curl);
        }
      }
    }

    public function cronjob()
    {
      $servicios = Servicio::whereNotNull('wialon')
                      ->where('active', true)
                      ->get();

      foreach ($servicios as $servicio) {
        $units = $this->getInfo($servicio);

        if($units){
          $this->sendData($units, $servicio);
        }
      }

    }

    public function getData(Request $request)
    {
      $servicio = Token::where('token', $request->header('Authorization'))->first()->servicio;
      return response()->json($this->getInfo($servicio));
    }
}
