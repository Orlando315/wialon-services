<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Pagina principal del sistema
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
      return view('dashboard');
    }

    /**
     * Mostrar el perfil del usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil()
    {
      return view('perfil');
    }

    /**
     * Actualizar la informacion del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePerfil(Request $request)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
      ]);

      Auth::user()->fill($request->only(['nombres', 'apellidos', 'email']));

      if(Auth::user()->save()){
        return redirect()->route('perfil')->with([
          'flash_message' => 'Perfil modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('perfil')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Cambiar la contraseña del usuario autenticado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed',
      ]);

      Auth::user()->password = bcrypt($request->password);

      if(Auth::user()->save()){
        return redirect()->route('perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contraseña cambiada exitosamente.'
        ]);
      }else{
        return redirect()->route('perfil')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
