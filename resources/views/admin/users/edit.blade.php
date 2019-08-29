@extends('layouts.app')

@section('title', 'Usuarios - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.users.index') }}"> Usuarios </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form class="" action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="POST">
              @csrf
              @method('PATCH')

              <h4>Editar Usuario</h4>

              <div class="form-group">
                <label class="control-label" for="role">Role: *</label>
                <select id="role" class="form-control" name="role" required>
                  <option value="">Seleccione...</option>
                  <option value="user" {{ old('role') == 'user' ? 'selected' : $user->role == 'user' ? 'selected' : '' }}> Usuario</option>
                  <option value="admin" {{ old('role') == 'admin' ? 'selected' : $user->role == 'admin' ? 'selected' : '' }}> Administrador</option>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="nombres">Nombres: *</label>
                <input id="nombres" class="form-control{{ $errors->has('nombres') ? ' is-invalid' : '' }}" type="text" name="nombres" maxlength="50" value="{{ old('nombres') ?? $user->nombres }}" placeholder="Nombres" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="apellidos">Apellidos: *</label>
                <input id="apellidos" class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos') ?? $user->apellidos }}" placeholder="Apellidos" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="email">Email: *</label>
                <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="email" maxlength="50" value="{{ old('email') ?? $user->email }}" placeholder="Email" required>
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
                <a class="btn btn-default" href="{{ route('admin.users.show', ['user' => $user]) }}"><i class="fa fa-reply"></i> Atras</a>
                <button class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
