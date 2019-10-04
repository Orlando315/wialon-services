<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Token;
use App\Servicio;

class TokensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return redirect()->route('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $servicio =  Servicio::find($request->servicio);

      if($servicio){
        $token = new Token;
        $token->generateToken();
        $token->servicio_id = $request->servicio;

        if(Auth::user()->tokens()->save($token)){
          return response()->json([
            'id' => $token->id,
            'token' => $token->token,
            'created' => $token->created_at->format('Y-m-d H:i:s')
          ]);
        }
      }

      return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function show(Token $token)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function edit(Token $token)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Token $token)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy(Token $token)
    {
      return response()->json($token->delete());
    }
}
