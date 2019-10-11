@extends('layouts.app')

@section('title', 'Suscripciones - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Inicio </a>
@endsection

@section('content')

  @include('partials.flash')
  <div class="row">
    <div class="col-12">
      <h2 class="text-center">Seleccione el plan de pago para su servicio</h2>
    </div>
  </div>
  <div class="row">
    @foreach($planes as $plan)
      <div class="col-lg-3 col-sm-6 card-plan-servicios">
        <a href="{{ route('suscripciones.subscribe', ['plan' => $plan->id, 'servicio' => $servicio]) }}">
          <div class="card card-stats">
            <div class="card-header">
              <h4 class="card-title text-center">
                {{ $plan->nombre }}
              </h4>
              <hr>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fa fa-cube text-danger"></i>
                  </div>
                </div>
                <div class="col-7">
                  <div class="numbers">
                    <p class="card-category">Costo</p>
                    <h4 class="card-title">{{ $plan->precio() }}</h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer ">
              <hr>
              <div class="stats">
                <i class="fa fa-calendar-o"></i> Meses: {{ $plan->meses }}
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>
  
@endsection
