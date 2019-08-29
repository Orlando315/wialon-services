@extends('layouts.blank')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Registro</div>

          <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
              @csrf

              <div class="form-group">
                <label for="nombres">Nombres</label>
                <input id="nombres" class="form-control{{ $errors->has('nombres') ? ' is-invalid' : '' }}" type="text" name="nombres" value="{{ old('nombres') }}" required autofocus>

                @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input id="apellidos" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" type="text" name="apellidos" value="{{ old('apellidos') }}" required autofocus>

                @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="password-confirm">Verificar contraseña</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
              </div>

              <div class="form-group mb-0 text-right">
                <a class="btn btn-default" href="{{ route('login') }}"> Volver </a>
                <button type="submit" class="btn btn-primary"> Enviar </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
