@extends('layouts.blank')

@section('title', 'Recuperar Contraseña - '.config('app.name'))

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Recuperar contraseña</div>

          <div class="card-body">
            @if(session('status'))
              <div class="alert alert-success" role="alert">
                {{ session('status') }}
              </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
              @csrf

              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required>

                @if($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary"> Enviar link de recuperación </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
