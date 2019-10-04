<?php

namespace App\Http\Middleware;

use Closure;
use App\Token;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if($request->header('Authorization')){
        $token = $request->header('Authorization');
        $servicioToken = Token::where('token', $token)->first();

        if($servicioToken && $servicioToken->token == $token){
          return $next($request, $servicioToken->servicio);
        }

        return response()->json([
          'message' => 'Token invalido.',
        ]);
      }

      return response()->json([
        'message' => 'Peticion invalida.',
      ]);
    }
}
