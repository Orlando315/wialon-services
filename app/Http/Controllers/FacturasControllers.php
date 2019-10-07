<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;

class FacturasControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $pendientes = Factura::pendientes();
      $pagadas = Factura::pagadas();
      $rechazadas = Factura::rechazadas();

      return view('facturas.index', compact('pendientes', 'pagadas', 'rechazadas'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
      return view('facturas.show', compact('factura'));
    }
}
