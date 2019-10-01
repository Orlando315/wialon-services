<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users = User::where('role', 'user')->get();
      $admins = User::where('role', 'admin')->get();

      return view('admin.users.index', compact('users', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.users.create');
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
        'role' => 'required|in:user,admin',
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'email' => 'required|email|unique:users,email',
      ]);

      $user = new User($request->all());
      $user->role = $request->role;
      $user->password = bcrypt($request->rut);

      if($user->save()){
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
          'flash_message' => 'Usuario agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('admin.users.create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
      return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
      $this->validate($request, [
        'role' => 'required|in:user,admin',
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id . ',id',
      ]);

      $user->fill($request->all());

      if($user->save()){
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
          'flash_message' => 'Usuario modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('admin.users.edit', ['user' => $user->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
      if($user->delete()){
        return redirect()->route('admin.users.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Sub-usuario eliminado exitosamente.'
        ]);
      }else{
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    /**
     * Modificar contraseña de un usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
    */
    public function password(Request $request, User $user)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed'
      ]);

      $user->password = bcrypt($request->password);

      if($user->save()){
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contraseña cambiada exitosamente.'
        ]);
      }else{
        return redirect()->route('admin.users.show', ['user' => $user->id])->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
