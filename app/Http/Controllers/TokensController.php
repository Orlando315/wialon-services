<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Token;

class TokensController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'field' => 'in:wialon,wisetrack',
        'token' => 'required|string',
      ]);

      Auth::user()->token->{$request->field} = $request->token;

      if(Auth::user()->token->save()){
        return response()->json(['response' => true]);
      }else{
        return response()->json(['response' => false]);
      }
    }
    /**
     * Nullify Wialon token
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function nullify()
    {
      return Auth::user()->token->disableToken();
    }
}
