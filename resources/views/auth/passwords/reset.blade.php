@extends('layouts.blank')

@section('title', 'Reestablecer Contraseña - '.config('app.name'))

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Reestablecer contraseña</div>

          <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
              @csrf
              <input type="hidden" name="token" value="{{ $token }}">

              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

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
                <button type="submit" class="btn btn-primary"> Reestablecer </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
