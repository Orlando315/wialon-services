<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factura;
use App\User;

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

      return view('admin.facturas.index', compact('pendientes', 'pagadas', 'rechazadas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $users = User::all();

      return view('admin.facturas.create', compact('users'));
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
        'user_id' => 'required',
        'servicio_id' => 'nullable',
        'descripcion' => 'required|string|max:250',
        'monto' => 'required|numeric|min:350|max:999999999999'
      ]);

      $factura = new Factura($request->all());
      $factura->generateCommerceOrder();

      if($factura->save()){
        $factura->createFlowPayment();

        return redirect()->route('facturas.show', ['factura' => $factura->id])->with([
          'flash_message' => 'Factura generada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->route('admin.facturas.create')->withErrors('Ha ocurrido un error');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }
}
