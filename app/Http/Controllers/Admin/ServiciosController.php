<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Servicio;
use App\User;

class ServiciosController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
      return view('admin.servicios.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
      $this->validate($request, [
        'alias' => 'nullable|string|max:50',
        'expiracion' => 'nullable|date-format:Y-m-d',
      ]);

      if($request->expiracion <= date('Y-m-d')){
        return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
            'flash_message' => 'La fecha de expiración debe ser mayor a la fecha de hoy.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
          ]);
      }

      $servicio = new Servicio([
        'alias'  => $request->alias
      ]);

      $servicio->expiration = $request->expiracion;
      $servicio->active = $request->expiracion != null;

      if($user->servicios()->save($servicio)){
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
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
     * Modificar la fecha de expiracion del Servicio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function expiration(Request $request, Servicio $servicio)
    {
      $this->validate($request, [
        'expiracion' => 'nullable|date-format:Y-m-d',
      ]);


      if($request->remove_expiration == 'on'){
        $servicio->expiration = null;

        // Si no hay una suscripcion activa, se desactiva el servicio
        if(!$servicio->activeSuscripcion){
          $servicio->active = false;
        }
      }else{
        if($request->expiracion <= date('Y-m-d')){
          return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with([
              'flash_message' => 'La fecha de expiración debe ser mayor a la fecha de hoy.',
              'flash_class' => 'alert-danger',
              'flash_important' => true
            ]);
        }

        $servicio->expiration = $request->expiracion;
        $servicio->active = true;
      }

      if($servicio->save()){
        $data = [
            'flash_message' => 'Fecha de expiración modificada exitosamente.',
            'flash_class' => 'alert-success'
          ];
      }else{
        $data = [
            'flash_message' => 'Ha ocurrido un error.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
          ];
      }

      return redirect()->route('servicios.show', ['servicio' => $servicio->id])->with($data);
    }

}
