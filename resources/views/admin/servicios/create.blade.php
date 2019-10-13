@extends('layouts.app')

@section('title','Servicios - '.config('app.name'))

@section('head')
  <!-- datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.users.show', ['user' => $user->id]) }}"> Servicios </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.servicios.store', ['user' => $user->id]) }}" method="POST">
              @csrf

              <h4>Agregar un servicio al usuario: {{ $user->nombres.' '.$user->apellidos }}</h4>

              <div class="form-group">
                <label class="control-label" for="alias">Alias:</label>
                <input id="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" type="text" name="alias" maxlength="50" value="{{ old('alias') }}" placeholder="Alias del servicio">
              </div>

              <div class="form-group">
                <label class="control-label" for="expiracion">Fecha de expiración:</label>
                <input id="expiracion" class="form-control{{ $errors->has('expiracion') ? ' is-invalid' : '' }}" type="text" name="expiracion" value="{{ old('expiracion') }}" placeholder="Fecha de expiración">
              </div>

              <div class="alert alert-dismissible alert-token" role="alert" style="display: none">
                <strong id="alert-message" class="text-center"></strong>

                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
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
                <a class="btn btn-default" href="{{ route('admin.users.show', ['user' => $user->id]) }}"><i class="fa fa-reply"></i> Atras</a>
                <button id="send-form" class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <!-- datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function (){
      $('#expiracion').datepicker({
        format: 'yyyy-mm-dd',
        startDate: 'today',
        language: 'es',
      });
    })
  </script>
@endsection
