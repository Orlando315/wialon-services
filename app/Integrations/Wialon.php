<?php
/* Classes for working with Wialon RemoteApi using PHP
*
* License:
* The MIT License (MIT)
*
* Copyright:
* 2002-2015 Gurtam, http://gurtam.com
*/
namespace App\Integrations;

class Wialon{

    private $sid = null;
    private $base_api_url = '';
    private $default_params = [];

    /** constructor */
    function __construct($scheme = 'http', $host = 'hst-api.wialon.com', $port = '', $sid = '', $extra_params = []) {
      $this->sid = '';
      $this->default_params = array_replace([], (array)$extra_params);
      $this->base_api_url = sprintf('%s://%s%s/wialon/ajax.html?', $scheme, $host, mb_strlen($port) > 0 ? ':' . $port : '');
    }

    /** sid setter */
    function set_sid($sid){
      $this->sid = $sid;
    }

    /** sid getter */
    function get_sid(){
      return $this->sid;
    }

    /** update extra parameters */
    public function update_extra_params($params){
      $this->default_params = array_replace($this->default_params, $extra_params);
    }

    /** RemoteAPI request performer
    * action - RemoteAPI command name
    * args - JSON string with request parameters
    */
    public function call($action, $args){
      $url = $this->base_api_url;
      if (stripos($action, 'unit_group') === 0) {
        $svc = $action;
        $svc[mb_strlen('unit_group')] = '/';
      } else {
        $svc = preg_replace('\'_\'', '/', $action, 1);
      }

      $params = [
        'svc' => $svc,
        'params' => $args,
        'sid' => $this->sid
      ];

      $all_params = array_replace($this->default_params , $params);
      $str = '';

      foreach ($all_params as $k => $v) {
        if(mb_strlen($str)>0)
          $str .= '&';
        $str .= $k.'='.urlencode(is_object($v) || is_array($v)  ? json_encode($v) : $v);
      }

      $ch = curl_init();
      $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $str
      );
      curl_setopt_array($ch, $options);
      
      $result = curl_exec($ch);
      
      if($result === FALSE){
        $result = '{"error":-1,"message":'.curl_error($ch).'}';
      }
      
      curl_close($ch);
      return $result;
    }

    /**
     * Login
     *
     * @param  string $token
     * @return $result - Server response
     */
    public function login($token) {
      $data = array(
        'token' => urlencode($token),
      );
      $result = $this->token_login(json_encode($data));
      $json_result = json_decode($result, true);
      if(isset($json_result['eid'])) {
        $this->sid = $json_result['eid'];
      }
      return $result;
    }
    
    /** Logout
    * return - server responce
    */
    public function logout() {
      $result = $this->core_logout();
      $json_result = json_decode($result, true);
      if($json_result && $json_result['error']==0)
        $this->sid = '';
      return $result;
    }
    
    /** Unknonwn methods hadler */
    public function __call($name, $args) {
      return $this->call($name, count($args) === 0 ? '{}' : $args[0]);
    }

    /** list of error messages with codes */
    protected static $errors = [
      0 => 'Successful operation',
      1 => 'Invalid session',
      2 => 'Invalid service name',
      3 => 'Invalid result',
      4 => 'Invalid input',
      5 => 'Error performing request',
      6 => 'Unknow error',
      7 => 'Access denied',
      8 => 'Invalid user name or password',
      9 => 'Authorization server is unavailable',
      10 => 'Reached limit of concurrent requests',
      1001 => 'No message for selected interval',
      1002 => 'Item with such unique property already exists',
      1003 => 'Only one request of given time is allowed at the moment',
      1005 => 'Execution time has exceeded the limit',
      1011 => 'Your IP has changed or session has expired',
    ];
    
    /** error message generator */
    public static function error($code){
      if(isset(self::$errors[$code])){
        $text = self::$errors[$code];
      }

      $text = isset(self::$errors[$code]) ? self::$errors[$code] : 'Unknow error code.';

      return sprintf('%d: %s', $code, $text);
    }
}
