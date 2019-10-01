@extends('layouts.app')

@section('title','Repetidores - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('servicios.show', ['servicio' => $repetidor->servicio_id]) }}"> Repetidores </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('repetidores.update', ['repetidor' => $repetidor->id]) }}" method="POST">
              @csrf
              @method('PATCH')

              <h4>Editar repetidor</h4>

              <div class="form-group">
                <label class="control-label" for="alias">Alias:</label>
                <input id="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" type="text" name="alias" maxlength="50" value="{{ old('alias') ?? $repetidor->alias }}" placeholder="Alias del repetidor">
              </div>

              <div class="form-group">
                <label class="control-label" for="token">Token Wisetrack: *</label>
                <input id="token" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" type="text" name="token" minlength="30" maxlength="50" value="{{ old('token') ?? $repetidor->token }}" placeholder="Token Wisetrack" requrired>
              </div>

              <div class="form-group">
                <label class="control-label" for="endpoint">Endpoint: *</label>
                <input id="endpoint" class="form-control{{ $errors->has('endpoint') ? ' is-invalid' : '' }}" type="text" name="endpoint" pattern="[a-zA-z]{5,30}" maxlength="30" value="{{ old('endpoint') ?? $repetidor->endpoint }}" placeholder="InsertaPosicion" required>
                <small class="form-text text-muted">http://ei.wisetrack.cl/API/Centinela/: <strong>endpoint</strong></small>
              </div>

              @if(count($errors) > 0)
                <div class="alert alert-danger alert-important">
                  <ul class="m-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="form-group text-right">
                <a class="btn btn-default" href="{{ route('servicios.show', ['servicios' => $repetidor->servicio_id]) }}"><i class="fa fa-reply"></i> Atras</a>
                <button id="send-form" class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
