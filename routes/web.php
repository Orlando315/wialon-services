<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'auth.login');
Route::view('login', 'auth.login');

Auth::routes();

/* --- Solo usuarios autenticados --- */
Route::group(['middleware' => 'auth'], function (){
  /* --- Dashboard --- */
  Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

  /* --- Perfil --- */
  Route::get('perfil', 'HomeController@perfil')->name('perfil');
  Route::patch('perfil', 'HomeController@updatePerfil')->name('perfil.update');
  Route::patch('perfil/password', 'HomeController@password')->name('perfil.password');

  /* --- Servicios --- */
  Route::resource('servicios', 'ServiciosController')
  ->except([
    'show'
  ]);
  Route::get('servicios/{servicio}/{repetidor?}', 'ServiciosController@show')->name('servicios.show');

  /* --- repetidores --- */
  Route::get('repetidores/{servicio}/create/', 'RepetidoresController@create')->name('repetidores.create');
  Route::post('repetidores/{servicio}/create/', 'RepetidoresController@store')->name('repetidores.store');
  Route::get('repetidores/{repetidor}/logs', 'RepetidoresController@logs')->name('repetidores.logs');
  Route::resource('repetidores', 'RepetidoresController')
  ->except([
    'create',
    'store'
  ])
  ->parameters([
      'repetidores' => 'repetidor'
  ]);

  /* --- Logs --- */
  Route::get('logs', 'LogsController@index')->name('logs.index');
  Route::post('logs/{servicio}/{type}', 'LogsController@logsByType');

  /* --- Admin --- */
  Route::prefix('/admin')->name('admin.')->namespace('Admin')->middleware('role:admin')->group(function(){        
    /* --- Users --- */
    Route::resource('users', 'UsersControllers');
    Route::patch('users/{user}/password', 'UsersControllers@password')->name('users.password');
  });
});
