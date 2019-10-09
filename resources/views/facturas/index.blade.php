@extends('layouts.app')

@section('title', 'Facturas - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('facturas.index') }}"> Facturas </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center icon-warning">
                <i class="fa fa-list-alt text-primary"></i>
              </div>
            </div>
            <div class="col-7">
              <div class="numbers">
                <p class="card-category">Facturas</p>
                <h4 class="card-title">{{ ($pendientes->count() + $pagadas->count() + $rechazadas->count()) }}</h4>
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
            <i class="fa fa-list-alt"></i> Facturas
          </h4>
        </div>
        <div class="card-body">
          <ul id="repetidores-tokens" class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="pendientes-tab" href="#pendientes" role="tab" data-toggle="tab" aria-controls="pendientes" aria-selected="true">Pendientes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pagadas-tab" href="#pagadas" role="tab" data-toggle="tab" aria-controls="pagadas" aria-selected="false">Pagadas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="rechazadas-tab" href="#rechazadas" role="tab" data-toggle="tab" aria-controls="rechazadas" aria-selected="false">Rechazadas</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="pendientes" class="tab-pane fade show active" role="tabpanel" aria-labelledby="pendientes-tab">
              <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col" class="text-center">Servicio</th>
                    <th scope="col" class="text-center">Monto</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($pendientes as $factura)
                    <tr>
                      <td scope="row">{{ $loop->index + 1 }}</td>
                      <td>{{ $factura->created_at }}</td>
                      <td>
                        @if($factura->hasServicio())
                          <a href="{{ route('servicios.show', ['servicio' => $factura->servicio_id]) }}">
                            {{ $factura->servicio->alias ?? 'Servicio #'.$factura->servicio_id }}
                          </a>
                        @else
                          -
                        @endif
                      </td>
                      <td>{{ $factura->monto() }}</td>
                      <td>{!! $factura->status() !!}</td>
                      <td>
                        <a class="btn btn-primary btn-link btn-sm" href="{{ route('facturas.show', ['factura' => $factura->id] )}}">
                          <i class="fa fa-search"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div><!-- .tab-pane -->

            <div id="pagadas" class="tab-pane fade" role="tabpanel" aria-labelledby="pagadas-tab" aria-expanded="false">
              <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col" class="text-center">Servicio</th>
                    <th scope="col" class="text-center">Monto</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($pagadas as $factura)
                    <tr>
                      <td scope="row">{{ $loop->index + 1 }}</td>
                      <td>{{ $factura->created_at }}</td>
                      <td>
                        @if($factura->hasServicio())
                          <a href="{{ route('servicios.show', ['servicio' => $factura->servicio_id]) }}">
                            {{ $factura->servicio->alias ?? 'Servicio #'.$factura->servicio_id }}
                          </a>
                        @else
                          -
                        @endif
                      </td>
                      <td>{{ $factura->monto() }}</td>
                      <td>{!! $factura->status() !!}</td>
                      <td>
                        <a class="btn btn-primary btn-link btn-sm" href="{{ route('facturas.show', ['factura' => $factura->id] )}}">
                          <i class="fa fa-search"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div><!-- .tab-pane -->

            <div id="rechazadas" class="tab-pane fade" role="tabpanel" aria-labelledby="rechazadas-tab" aria-expanded="false">
              <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col" class="text-center">Servicio</th>
                    <th scope="col" class="text-center">Monto</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($rechazadas as $factura)
                    <tr>
                      <td scope="row">{{ $loop->index + 1 }}</td>
                      <td>{{ $factura->created_at }}</td>
                      <td>
                        @if($factura->hasServicio())
                          <a href="{{ route('servicios.show', ['servicio' => $factura->servicio_id]) }}">
                            {{ $factura->servicio->alias ?? 'Servicio #'.$factura->servicio_id }}
                          </a>
                        @else
                          -
                        @endif
                      </td>
                      <td>{{ $factura->monto() }}</td>
                      <td>{!! $factura->status() !!}</td>
                      <td>
                        <a class="btn btn-primary btn-link btn-sm" href="{{ route('facturas.show', ['factura' => $factura->id] )}}">
                          <i class="fa fa-search"></i>
                        </a>
                      </td>
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
