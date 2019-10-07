@extends('layouts.app')

@section('title', 'Facturas - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route(Auth::user()->isAdmin() ? 'admin.facturas.index' : 'facturas.index') }}"> Facturas </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <a class="btn btn-default" href="{{ route(Auth::user()->isAdmin() ? 'admin.facturas.index' : 'facturas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>
  
  @include('partials.flash')

  <div class="row mt-2">
    <div class="col-md-4">
      <div class="card card-information">
        <div class="card-header">
          <h4 class="card-title">
            Información
          </h4>
        </div><!-- .card-header -->
        <div class="card-body">
          <strong>Commerce Order</strong>
          <p class="text-muted">
            {{ $factura->commerceOrder }}
          </p>
          <hr>
          
          @if(Auth::user()->isAdmin())
          <strong>Nombre</strong>
          <p class="text-muted">
            <a href="{{ route('admin.users.show', ['user' => $factura->user_id]) }}" title="{{ $factura->user->nombres.' '.$factura->user->apellidos }}">
              {{ $factura->user->email }}
            </a>
          </p>
          <hr>
          @endif
          
          @if($factura->hasServicio())
          <strong>Servicio</strong>
          <p class="text-muted">
            <a href="{{ route('servicios.show', ['servicio' => $factura->servicio_id]) }}">
              {{ $factura->servicio->alias ?? 'Servicio #'.$factura->servicio_id }}
            </a>
          </p>
          <hr>
          @endif

          <strong>Monto</strong>
          <p class="text-muted">
            {{ $factura->monto() }}
          </p>
          <hr>

          <strong>Descripción</strong>
          <p class="text-muted">
            {{ $factura->descripcion }}
          </p>
          <hr>

          <strong>Status</strong>
          <p class="text-muted">
            {!! $factura->status() !!}
          </p>
        </div>
        <div class="card-footer text-center">
          <hr>
          <small class="text-muted">
            {{ $factura->created_at }}
          </small>
        </div><!-- .card-footer -->
      </div><!-- .card -->
    </div>

    <div class="col-md-4">
      <div class="card card-information">
        <div class="card-header">
          <h4 class="card-title">
            Pago
          </h4>
        </div><!-- .card-header -->
        <div class="card-body">
          @if($factura->status !== null)
            @if($factura->pago)
              <strong># Orden</strong>
              <p class="text-muted">
                {{ $factura->pago->flow_order }}
              </p>
              <hr>

              <strong>Emitida a</strong>
              <p class="text-muted">
                {{ $factura->pago->payer }}
              </p>
              <hr>
              
              @if($factura->pago->isCompleto())
                <strong>Pagada el</strong>
                <p class="text-muted">
                  {{ $factura->pago->payment_date }}
                </p>
                <hr>

                <strong>Medio</strong>
                <p class="text-muted">
                  {{ $factura->pago->medio }}
                </p>
                <hr>

                <strong>Monto</strong>
                <p class="text-muted">
                  {{ $factura->pago->amount() }}
                </p>
                <hr>
              @endif

              @if($factura->pago->isCompleto() && Auth::user()->isAdmin())

                <strong>Comisión</strong>
                <p class="text-muted">
                  {{ $factura->pago->fee() }}
                </p>
                <hr>

                <strong>Taxes</strong>
                <p class="text-muted">
                  {{ $factura->pago->taxes() }}
                </p>
                <hr>

                <strong>Balance</strong>
                <p class="text-muted">
                  {{ $factura->pago->balance() }}
                </p>
                <hr>
              @endif

                <strong>Status</strong>
                <p class="text-muted">
                  {!! $factura->pago->status() !!}
                </p>
                <hr>
            @else
              <p class="text-center text-muted">La factura no tiene un pago registrado.</p>
            @endif
          @elseif($factura->user_id == Auth::id())
            <p class="text-center">
              <a class="btn btn-fill btn-primary" href="{{ $factura->pagoUrl() }}">Realizar pago</a>
            </p>
          @endif
        </div>
      </div><!-- .card -->
    </div>
  </div>
@endsection
