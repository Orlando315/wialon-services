@extends('layouts.app')

@section('title', 'Facturas - '.config('app.name'))

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('js/plugins/select2/select2.min.css') }}">
@endsection

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.facturas.index') }}"> Facturas </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.facturas.store') }}" method="POST">
              @csrf

              <h4>Agregar Factura</h4>

              <div class="form-group">
                <label class="control-label" for="user_id">Usuario: *</label>
                <select id="user_id" class="form-control" name="user_id" required>
                  <option value="">Seleccione...</option>
                  @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}> {{ $user->email }} | {{ $user->nombres.' '.$user->apellidos }}</option>
                  @endforeach
                </select>
              </div>


              <div class="form-group">
                <label class="control-label" for="servicio_id">Servicio:</label>
                <select id="servicio_id" class="form-control" name="servicio_id" disabled>
                  <option value="">Seleccione...</option>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="descripcion">Descripción: *</label>
                <input id="descripcion" class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" type="text" name="descripcion" maxlength="250" value="{{ old('descripcion') }}" placeholder="Descripción" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="monto">Monto: *</label>
                <input id="monto" class="form-control{{ $errors->has('monto') ? ' is-invalid' : '' }}" type="number" name="monto" min="350" max="999999999999" value="{{ old('monto') }}" placeholder="Monto" required>
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
                <a class="btn btn-default" href="{{ route('admin.facturas.index') }}"><i class="fa fa-reply"></i> Atras</a>
                <button class="btn btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script type="text/javascript" src="{{ asset( 'js/plugins/select2/select2.min.js' ) }}"></script>
  <script type="text/javascript">
    $(document).ready(function (){
      $('#user_id').select2({
        placeholder: 'Seleccione...',
      });

      $('#servicio_id').select2({
        placeholder: 'Seleccione...',
        disabled: true,
        language: {
          noResults: function () {
            return 'No se encontraron servicios.';
          }
        }
      });

      $('#user_id').change(getServicios)
      $('#user_id').change()
    });

    function getServicios(){
      let user = $(this).val()

      if(!user){ return false }

      $('#servicio_id').empty()

      $.ajax({
        type: 'POST',
        url: `{{ route("admin.users.index") }}/${user}/get/servicios`,
        data: {
          _token: '{{ @csrf_token() }}',
        },
        dataType: 'json',
      })
      .done(function (response){
        $('#servicio_id').append(`<option value=""></option>`)
        $.each(response, function (k, v){
          $('#servicio_id').append(`<option value="${v.id}">${v.alias || 'Servicio #'+v.id }</option>`)
        })

        $('#servicio_id').prop('disabled', false)
      })
      .fail(function (){
        $('#servicio_id').select2({
          placeholder: 'Seleccione...',
          disabled: true,
          language: {
            noResults: function () {
              return 'No se encontraron servicios.';
            }
          }
        });
      })
    }
  </script>
@endsection
