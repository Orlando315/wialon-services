@extends('layouts.app')

@section('title', 'Perfil - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Perfil </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <a class="btn btn-default" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <button class="btn btn-warning" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>
    </div>
  </div>

  @include('partials.flash')

  <div class="row" style="margin-top: 20px">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form class="" action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
              <label class="control-label" for="nombres">Nombres: *</label>
              <input id="nombres" class="form-control{{ $errors->has('nombres') ? ' is-invalid' : '' }}" type="text" name="nombres" maxlength="50" value="{{ old('nombres') ?? Auth::user()->nombres }}" placeholder="Nombres" required>
            </div>

            <div class="form-group">
              <label class="control-label" for="apellidos">Apellidos: *</label>
              <input id="apellidos" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos') ?? Auth::user()->apellidos }}" placeholder="Apellidos" required>
            </div>

            <div class="form-group">
              <label class="control-label" for="email">Email: *</label>
              <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="email" maxlength="50" value="{{ old('email') ?? Auth::user()->email }}" placeholder="Email" required>
            </div>
            
            @if(count($errors) > 0)
            <div class="alert alert-danger alert-important">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <div class="form-group text-right">
              <button class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="passModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="passModalLabel">Cambiar contraseña</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-center">
            <form class="col-md-8" action="{{ route('perfil.password') }}" method="POST">
              @csrf
              @method('PATCH')

              <div class="form-group">
                <label>Contraseña nueva: *</label>
                <input id="password" class="form-control" type="password" pattern=".{6,}" name="password" required>
                <small class="text-muted">Debe contener al menos 6 caracteres.</small>
              </div>
              <div class=" form-group">
                <label>Verificar: *</label>
                <input id="password_confirmation" class="form-control" type="password" pattern=".{6,}" name="password_confirmation" required>
                <small class="text-muted">Debe contener al menos 6 caracteres.</small>
              </div>

              @if (count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif

              <center>
                <button class="btn btn-danger btn-fill" type="submit">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
