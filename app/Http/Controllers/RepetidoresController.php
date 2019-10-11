<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repetidor;
use App\Servicio;

class RepetidoresController extends Controller
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
    public function create(Servicio $servicio)
    {
      if(!$servicio->active){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id]);
      }

      return view('servicios.repetidores.create', compact('servicio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Servicio $servicio)
    {
      $this->validate($request, [
        'alias' => 'nullable|string|max:50',
        'token' => 'required|string|min:30|max:50',
        'endpoint' => 'required|string|max:30',
      ]);

      $repetidor = new Repetidor($request->all());
      $repetidor->user_id = Auth::id();

      if($servicio->repetidores()->save($repetidor)){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Repetidor agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('repetidores.create', ['servicio' => $servicio->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repetidor  $repetidor
     * @return \Illuminate\Http\Response
     */
    public function show(Repetidor $repetidor)
    {
      return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Repetidor  $repetidor
     * @return \Illuminate\Http\Response
     */
    public function edit(Repetidor $repetidor)
    {
      return view('servicios.repetidores.edit', compact('repetidor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Repetidor  $repetidor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repetidor $repetidor)
    {
      $this->validate($request, [
        'alias' => 'nullable|string|max:50',
        'token' => 'required|string|min:30|max:50',
        'endpoint' => 'required|string|max:30',
      ]);

      $repetidor->fill($request->all());

      if($repetidor->save()){
        return redirect()->route('servicios.show', ['servicio' => $repetidor->servicio_id])->with([
          'flash_message' => 'Repetidor modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('repetidor.edit', ['repetidor' => $repetidor->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Repetidor  $repetidor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Repetidor $repetidor)
    {
      return response()->json($repetidor->delete());
    }
}
