@extends('layouts.app')

@section('title', 'Inicio - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Inicio </a>
@endsection

@section('content')

  @include('partials.flash')

  <div class="row">
    @foreach(Auth::user()->servicios as $servicio)
      <div class="col-md-6">
        <div class="card table-with-links">
          <div class="card-header">
            <h4 class="card-title text-center">
              <a href="{{ route('servicios.show', ['servicio' => $servicio->id]) }}" title="">
                {{ $servicio->alias() }}
              </a>
            </h4>
            <p class="card-category text-center{{ $servicio->wialon ? '' : ' text-danger' }}">{{ $servicio->wialon ? '' : '-NO HAY TOKEN REGISTRADO-' }}</p>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Repetidor</th>
                  <th>Último status</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                @foreach($servicio->repetidores as $repetidor)
                  <tr>
                    <td title="{{ $repetidor->token }}">{{ $repetidor->alias ?? $repetidor->token }}</td>
                    <td class="text-center" title="{{ $repetidor->lastMessage() }}">{!! $repetidor->lastStatus() !!}</td>
                    <td>
                      <div class="dropdown btn-config-dropdown">
                        <button class="btn dropdown-toggle btn-fill btn-sm" type="button" id="dropdownConfigLink-{{ $repetidor->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-cogs"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownConfigLink-{{ $repetidor->id }}">
                          <a class="dropdown-item" href="{{ route('servicios.show', ['servicio' => $repetidor->servicio_id, 'log' => $repetidor->id]) }}"><i class="fa fa-file-text-o"></i> Logs</a>
                          <a class="dropdown-item" href="{{ route('repetidores.edit', ['repetidor' => $repetidor->id]) }}"><i class="fa fa-pencil"></i> Editar</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  
@endsection
