@extends('layouts.blank')

@section('head')
  <style type="text/css">
    .form-signin{
      width: 100%;
      max-width: 420px;
      padding: 15px;
      margin: auto;
      border-radius: 4px;
      background-color: #FFFFFF;
      border: 1px solid #EEE;
    }
  </style>
@endsection

@section('content')
  <form class="form-signin" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="text-center mb-4">
      @if(config('app.logo'))
        <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" height="72">
      @endif
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

    <div class="form-group">
      <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
    </div>

    <div class="form-group">
      <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="contraseña" required>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary btn-fill btn-block">
        Login
      </button>
      <p class="text-center" style="margin: 15px 0 0 0">
        ¿No posees una cuenta?
        <a href="{{ route('register') }}" title="Registro">Registrate</a>
      </p>
      <p class="text-center">
        ¿Olvidaste tu contraseña?
        <a href="{{ route('password.request') }}" title="Recuperar contraseña">Recuperar</a>.
      </p>
    </div>
  </form>
@endsection
