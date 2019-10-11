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

/* --- Confirmacion de Pago Flow --- */
Route::post('pagos/flow/confirmation', 'PagosControllers@confirmation')->name('pagos.confirmation');
/* --- Confirmacion de Suscripcion Flow --- */
Route::post('suscripciones/flow/confirmation', 'SuscripcionesControllers@confirmation')->name('suscripciones.confirmation');

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
    'show',
    'create',
  ]);
  Route::get('servicios/{servicio}/{repetidor?}', 'ServiciosController@show')->name('servicios.show');

  /* --- repetidores --- */
  Route::get('repetidores/{servicio}/create/', 'RepetidoresController@create')->name('repetidores.create');
  Route::post('repetidores/{servicio}/', 'RepetidoresController@store')->name('repetidores.store');
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

  /* --- Suscripciones --- */
  Route::get('suscripciones/subscribe/{plan}/{servicio?}', 'SuscripcionesControllers@subscribe')->name('suscripciones.subscribe');
  Route::get('suscripciones/planes/{servicio?}', 'SuscripcionesControllers@planes')->name('suscripciones.planes');
  Route::post('suscripciones/{servicio}/cancel', 'SuscripcionesControllers@cancel')->name('suscripciones.cancel');

  /* --- Pagos --- */
  Route::get('pagos', 'PagosControllers@index')->name('pagos.index');

  /* --- Tokens --- */
  Route::resource('tokens', 'TokensController')
  ->only([
    'index',
    'store',
    'destroy',
  ]);

  /* --- Facturas --- */
  Route::get('facturas/', 'FacturasControllers@index')->name('facturas.index');
  Route::get('facturas/{factura}', 'FacturasControllers@show')->name('facturas.show');

  /* --- Admin --- */
  Route::prefix('/admin')->name('admin.')->namespace('Admin')->middleware('role:admin')->group(function(){        
    /* --- Users --- */
    Route::resource('users', 'UsersControllers');
    Route::post('users/{user}/get/servicios', 'UsersControllers@servicios');
    Route::patch('users/{user}/password', 'UsersControllers@password')->name('users.password');

    /* --- Planes --- */
    Route::resource('planes', 'PlanesControllers')
    ->parameters([
      'planes' => 'plan'
    ]);

    /* --- Facturas --- */
    Route::resource('facturas', 'FacturasControllers')
    ->only([
      'index',
      'create',
      'store'
    ]);
  });
});
