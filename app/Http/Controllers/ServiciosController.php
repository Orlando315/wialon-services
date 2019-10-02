<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Servicio;
use App\Repetidor;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'alias' => 'nullable|string|max:50',
        'token' => 'required|string|min:70|max:80'
      ]);

      $servicio = new Servicio([
        'alias'  => $request->alias,
        'wialon' => $request->token,
        'wialon_expiration' => date('Y-m-d H:i:s', strtotime('+30 days'))
      ]);

      if(Auth::user()->servicios()->save($servicio)){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Servicio agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('servicios.create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Servicio  $servicio
     * @param  \App\Repetidor  $repetidor
     * @return \Illuminate\Http\Response
     */
    public function show(Servicio $servicio, Repetidor $repetidor = null)
    {
      $logs = (object)[
        'id' => $repetidor ? $repetidor->id : $servicio->id,
        'all' => $repetidor ? $repetidor->logsAll : $servicio->logsAll,
        'success' => $repetidor ? $repetidor->logsSuccess : $servicio->logsSuccess,
        'error' => $repetidor ? $repetidor->logsError : $servicio->logsError,
        'type' => $repetidor ? 'repetidor' : 'servicio',
      ];

      return view('servicios.show', compact('servicio', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function edit(Servicio $servicio)
    {
      return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servicio $servicio)
    {
      $this->validate($request, [
        'alias' => 'nullable|string|max:50',
        'token' => 'required|string|max:80'
      ]);

      $servicio->alias  = $request->alias;
      $servicio->wialon = $request->token;

      if($servicio->save()){
        if(isset($servicio->getChanges()['wialon'])){
          $servicio->updateExpiration();
        }

        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Servicio modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('servicios.edit', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servicio $servicio)
    {
      if($servicio->delete()){
        return redirect()->route('dashboard')->with([
          'flash_message' => 'Servicio eliminado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }
}
