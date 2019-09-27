<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Tokens;
use Wialon;

class WialonController extends Controller
{ 
    protected function getInfo($token)
    {
      $wialon_api = new Wialon();

      $result = json_decode($wialon_api->login($token->wialon), true);

      if(!isset($result['error'])){
        $units = json_decode($wialon_api->core_update_data_flags('{"spec":[{"type":"type","data":"avl_unit","flags":1025,"mode":0}]}'));
        $wialon_api->logout();
        // $this->sendData($units);
        return $units;
      }elseif($result['error'] == 1 || $result['error'] == 7 || $result['error'] == 8){
        Log::channel('tokens')->info('Desactivar token de Wialon al usuario: '.$token->user_id, ['error' => $result['error']]);
        $token->disableToken();
      }
    }

    protected function sendData($units, $token)
    {
      foreach ($units as $unit) {
        $curl = curl_init('http://ei.wisetrack.cl/API/Centinela/InsertarPosicion');

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
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization: Bearer ' . $token->wisetrack]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl), true);

        if(isset($result['fault']) && $result['fault']['code'] == 900901){
          Log::channel('tokens')->info('Desactivar token de Wisetrack al usuario: ' . $token->user_id, $result['fault']);
          $token->disableToken('wisetrack');
          break;
        }
        dd($result);
        curl_close($curl);
      }
    }

    public function cronjob()
    {
      $tokens = Token::whereNotNull('wialon')
                      ->whereNotNull('wisetrack')
                      ->get();

      foreach ($tokens as $token) {
        $units = $this->getInfo($token);
        $this->sendData($units, $token);
      }

    }
}
