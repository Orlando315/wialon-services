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

  /* --- Admin --- */
  Route::prefix('/admin')->name('admin.')->namespace('Admin')->middleware('role:admin')->group(function(){        
    /* --- Users --- */
    Route::resource('users', 'UsersControllers');
    Route::patch('users/{user}/password', 'UsersControllers@password')->name('users.password');
  });
});
