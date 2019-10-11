@extends('layouts.app')

@section('title', 'Planes - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.planes.index') }}"> Planes </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <a class="btn btn-default" href="{{ route('admin.planes.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @if(!$plan->deleted_at)
        <a class="btn btn-success" href="{{ route('admin.planes.edit', ['user' => $plan->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <button class="btn btn-fill btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
    </div>
  </div>
  
  @include('partials.flash')

  @if($plan->deleted_at)
    <div class="row justify-content-md-center mt-2">
      <div class="col-md-6">
        <div class="alert alert-danger alert-important" role="alert">
          <strong class="text-center">Plan eliminado. </strong>
          Este plan fue eliminado el: {{ $plan->deleted_at }}
        </div>
      </div>
    </div>
  @endif

  <div class="row" style="margin-top: 20px">
    <div class="col-md-3">
      <div class="card card-information">
        <div class="card-header">
          <h4 class="card-title">
            Información
          </h4>
        </div><!-- .card-header -->
        <div class="card-body">
          <strong>Plan ID</strong>
          <p class="text-muted">
            {{ $plan->planId }}
          </p>
          <hr>

          <strong>Nombre</strong>
          <p class="text-muted">
            {{ $plan->nombre }}
          </p>
          <hr>

          <strong>Precio</strong>
          <p class="text-muted">
            {{ $plan->precio() }}
          </p>
          <hr>

          <strong>Meses</strong>
          <p class="text-muted">
            {{ $plan->meses }}
          </p>

          <strong>Status</strong>
          <p class="text-muted">
            {!! $plan->status() !!}
          </p>
        </div>
        <div class="card-footer text-center">
          <hr>
          <small class="text-muted">
            {{ $plan->created_at }}
          </small>
        </div><!-- .card-footer -->
      </div><!-- .card -->
    </div>

    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <table class="table data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Servicio</th>
                <th>Vencimiento</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($plan->servicios as $servicio)
                <tr>
                  <td scope="row">{{ $loop->index + 1 }}</td>
                  <td title="{{ $servicio->user->nombres.' '.$servicio->user->apellidos }}">
                    <a href="{{ route('admin.users.show', ['user' => $servicio->user_id]) }}">
                      {{ $servicio->user->email }}
                    </a>
                  </td>
                  <td>
                    <a href="{{ route('servicios.show', ['servicio' => $servicio->id]) }}">
                      {{ $servicio->alias ?? 'Servicio #'.$servicio->id }}
                    </a>
                  </td>
                  <td>{{ $servicio->pivot->period_end }}</td>
                  <td>{!! $servicio->status() !!}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="delModalLabel">Eliminar Plan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-md-center">
            <form class="col-md-10" action="{{ route('admin.planes.destroy', ['plan' => $plan->id]) }}" method="POST">
              @csrf
              @method('DELETE')

              <p class="text-center">¿Esta seguro de eliminar este Plan?</p><br>
              <p class="text-center">Los servicios que tengan este plan seguiran activos hasta el final de su periodo.</p>

              <center>
                <button class="btn btn-fill btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
