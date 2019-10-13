@extends('layouts.app')

@section('title', 'Pagos - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('pagos.index') }}"> Pagos </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center icon-warning">
                <i class="fa fa-credit-card text-primary"></i>
              </div>
            </div>
            <div class="col-7">
              <div class="numbers">
                <p class="card-category">Pagos</p>
                <h4 class="card-title">{{ ($facturas->count() + $suscripciones->count()) }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card card-dropdown-tabs">
        <div class="card-header">
          <h4 class="card-title">
            <i class="fa fa-credit-card"></i> Pagos
          </h4>
        </div>
        <div class="card-body">
          <ul id="repetidores-tokens" class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="facturas-tab" href="#facturas" role="tab" data-toggle="tab" aria-controls="facturas" aria-selected="true">Pagadas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="suscripciones-tab" href="#suscripciones" role="tab" data-toggle="tab" aria-controls="suscripciones" aria-selected="false">Suscripciones</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="facturas" class="tab-pane fade show active" role="tabpanel" aria-labelledby="facturas-tab">
              <table class="table data-table table-striped table-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Factura</th>
                    <th scope="col" class="text-center">Descripición</th>
                    <th scope="col" class="text-center">Medio</th>
                    <th scope="col" class="text-center">Monto</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Fecha</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($facturas as $pago)
                    <tr>
                      <td scope="row">{{ $loop->index + 1 }}</td>
                      <td>
                        <a href="{{ route('facturas.show', ['factura' => $pago->factura->id]) }}">
                          {{ $pago->flow_order }}
                        </a>
                      </td>
                      </td>
                      <td>{{ $pago->factura->descripcion }}</td>
                      <td>{{ $pago->medio }}</td>
                      <td>{{ $pago->amount() }}</td>
                      <td>{!! $pago->status() !!}</td>
                      <td>{{ $pago->payment_date }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div><!-- .tab-pane -->

            <div id="suscripciones" class="tab-pane fade" role="tabpanel" aria-labelledby="suscripciones-tab" aria-expanded="false">
              <table class="table data-table table-striped table-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Suscripción</th>
                    <th scope="col" class="text-center">Servicio</th>
                    <th scope="col" class="text-center">Medio</th>
                    <th scope="col" class="text-center">Monto</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Fecha</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($suscripciones as $pago)
                    <tr>
                      <td scope="row">{{ $loop->index + 1 }}</td>
                      <td> {{ $pago->plan()->nombre }} </td>
                      <td>
                        <a href="{{ route('servicios.show', ['servicio' => $pago->servicio()->id]) }}">
                          {{ $pago->servicio()->alias() }}
                        </a>
                      </td>
                      <td>{{ $pago->medio }}</td>
                      <td>{{ $pago->amount() }}</td>
                      <td>{!! $pago->status() !!}</td>
                      <td>{{ $pago->payment_date }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div><!-- .tab-pane -->
          </div><!-- .tab-content -->
        </div><!-- .card-body -->
      </div>
    </div>
  </div>
@endsection
