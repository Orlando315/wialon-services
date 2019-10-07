<?php
/**
 * Integration with FLow API v3.0.1
 *
 */
namespace App\Integrations\Flow;

use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\App;

class Flow{
    private $sandbox = false;
    private $key = null;
    private $secret = null;
    private $serverUrl = '';

    public function __construct($sandbox = null)
    {
      $sandbox = $sandbox ?? config('flow.sandbox');
      $this->setSandbox($sandbox);
      $this->setCredentials();
      $this->setServerUrl();
    }

    /**
     * Establecer credenciales (Key, Secret)
     */
    private function setCredentials()
    {
      $this->key    = $this->sandbox ? config('flow.credentials.sandbox.key') : config('flow.credentials.production.key');
      $this->secret = $this->sandbox ? config('flow.credentials.sandbox.secret') : config('flow.credentials.production.secret');
    }

    /**
     * Establecer url del servidor en base al estado del Sandbox
     */
    private function setServerUrl()
    {
      $this->serverUrl = $this->sandbox ? config('flow.server.sandbox') : config('flow.server.production');
    }

    /**
     * Establecer modo Sandbox true/false
     *
     * @param bool $sandbox
     */
    public function setSandbox(bool $sandbox)
    {
      $this->sandbox = $sandbox;
    }

    /**
     * Funcion que invoca un servicio del API de Flow
     *
     * @param string $service Nombre del servicio a ser invocado
     * @param array $params datos a ser enviados
     * @param string $method metodo http a utilizar
     * @return mixed objeto $body, o False en caso de error
     */
    public function send($service, $params = [], $method = 'GET') {
      $method = strtoupper($method);
      $url = $this->serverUrl . $service;
      $params = ['apiKey' => $this->key] + $params;
      $data = $this->getPack($params, $method);
      $sign = $this->sign($params);

      if($method == 'GET') {
        $response = $this->httpGet($url, $data, $sign);
      } else {
        $response = $this->httpPost($url, $data, $sign);
      }
      
      if(isset($response['info'])) {
        $code = $response['info']['http_code'];
        $body = json_decode($response['output']);

        if($code == '200') {
          return $body;
        }elseif(in_array($code, ['400', '401'])){
          LaravelLog::channel('flow')->error('Error: ' . $code, ['result' => $body]);
        }else{
          LaravelLog::channel('flow')->error('Unexpected error. HTTP_CODE: ' . $code, ['result' => $body]);
        }
      }else{
        LaravelLog::channel('flow')->error('Unexpected error: ', ['result' => $response]);
      }

      return false;
    }
    
    /**
     * Funcion que empaqueta los datos de parametros para ser enviados
     *
     * @param array $params datos a ser empaquetados
     * @param string $method metodo http a utilizar
     */
    private function getPack($params, $method) {
      $keys = array_keys($params);
      sort($keys);
      $data = '';

      foreach ($keys as $key) {
        if($method == 'GET') {
          $data .= '&' . rawurlencode($key) . '=' . rawurlencode($params[$key]);
        } else {
          $data .= '&' . $key . '=' . $params[$key];
        }
      }

      return substr($data, 1);
    }
    
    /**
     * Funcion que firma los parametros
     *
     * @param string $params Parametros a firmar
     * @return string de firma
     */
    private function sign($params) {
      $keys = array_keys($params);
      sort($keys);
      $toSign = '';

      foreach ($keys as $key) {
        $toSign .= '&' . $key . '=' . $params[$key];
      }

      $toSign = substr($toSign, 1);

      if(!function_exists('hash_hmac')) {
        throw new \Exception('function hash_hmac not exist', 1);
      }

      return hash_hmac('sha256', $toSign , $this->secret);
    }
    
    
    /**
     * Funcion que hace el llamado via http GET
     *
     * @param string $url url a invocar
     * @param array $data datos a enviar
     * @param string $sign firma de los datos
     * @return string en formato JSON 
     */
    private function httpGet($url, $data, $sign) {
      $url = $url . '?' . $data . '&s=' . $sign;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      
      if(App::environment() === 'local'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      }

      $output = curl_exec($ch);

      if($output === false) {
        $error = curl_error($ch);
        throw new \Exception($error, 1);
      }

      $info = curl_getinfo($ch);
      curl_close($ch);
      return ['output' =>$output, 'info' => $info];
    }
    
    /**
     * Funcion que hace el llamado via http POST
     *
     * @param string $url url a invocar
     * @param array $data datos a enviar
     * @param string $sign firma de los datos
     * @return string en formato JSON 
     */
    private function httpPost($url, $data, $sign){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data . '&s=' . $sign);

      if(App::environment() === 'local'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      }

      $output = curl_exec($ch);

      if($output === false) {
        $error = curl_error($ch);
        throw new \Exception($error, 1);
      }

      $info = curl_getinfo($ch);
      curl_close($ch);
      return array('output' =>$output, 'info' => $info);
    }
}
