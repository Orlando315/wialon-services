<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Plan;

class PlanesControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $planes = Plan::all();

      return view('admin.planes.index', compact('planes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.planes.create');
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
        'nombre' => 'required|string|max:50',
        'precio' => 'required|numeric|min:1|max:999999999999',
        'meses' => 'required|in:1,3,6,12'
      ]);

      $plan = new Plan($request->all());

      if($plan->createFlowPlan()){
        return redirect()->route('admin.planes.show', ['plan' => $plan->id])->with([
          'flash_message' => 'Plan agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('admin.planes.create')->withErrors('Ha ocurrido un error');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
      return view('admin.planes.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
      return view('admin.planes.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'precio' => 'required|numeric|min:1|max:999999999999',
        'meses' => 'required|in:1,3,6,12'
      ]);

      $plan->fill($request->all());

      if($plan->editFlowPlan()){
        return redirect()->route('admin.planes.show', ['plan' => $plan->id])->with([
          'flash_message' => 'Plan modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('admin.planes.edit')->withErrors('Ha ocurrido un error.');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
      if($plan->deleteFlowPlan()){
        $data = [
          'flash_message' => 'Plan eliminado exitosamente.',
          'flash_class' => 'alert-success'
          ];
      }else{
        $data = [
          'flash_message' => 'Ha ocurrido un error al intentar eliminar el Plan.',
          'flash_class' => 'alert-success'
          ];
      }

      return redirect()->route('admin.planes.show', ['plan' => $plan->id])->with($data);
    }
}
