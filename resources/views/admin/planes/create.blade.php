@extends('layouts.app')

@section('title', 'Planes - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.planes.index') }}"> Planes </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.planes.store') }}" method="POST">
              @csrf

              <h4>Agregar Plan</h4>

              <div class="form-group">
                <label class="control-label" for="nombre">Nombre: *</label>
                <input id="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="precio">Precio: *</label>
                <input id="precio" class="form-control{{ $errors->has('precio') ? ' is-invalid' : '' }}" type="number" name="precio" min="1" max="999999999999" value="{{ old('precio') }}" placeholder="Precio" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="meses">Meses: *</label>
                <select id="meses" class="form-control" name="meses" required>
                  <option value="">Seleccione...</option>
                  <option value="1" {{ old('meses') == '1' ? 'selected' : '' }}> 1</option>
                  <option value="3" {{ old('meses') == '3' ? 'selected' : '' }}> 3</option>
                  <option value="6" {{ old('meses') == '6' ? 'selected' : '' }}> 6</option>
                  <option value="12" {{ old('meses') == '12' ? 'selected' : '' }}> 12</option>
                </select>
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
                <a class="btn btn-default" href="{{ route('admin.planes.index') }}"><i class="fa fa-reply"></i> Atras</a>
                <button class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
