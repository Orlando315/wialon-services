@extends('layouts.app')

@section('title','Servicios - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Servicios </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <a class="btn btn-default" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-success" href="{{ route('servicios.edit', ['servicio' => $servicio->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-fill btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>
  
  @include('partials.flash')

  <div class="row mt-2">
    <div class="col-md-12">
      <div class="card card-servicio">
        <div class="card-header">
          <h4 class="card-title">
            {{ $servicio->alias ?? 'Servicio' }}
            <a class="btn btn-primary btn-fill btn-xs" href="{{ route('repetidores.create', ['servicio' => $servicio->id]) }}" title="Agregar repetidor">
              <i class="fa fa-plus"></i> Agregar repetidor
            </a>
          </h4>
          <p class="card-category{{ $servicio->wialon ? '' : ' text-danger' }}">{{ $servicio->wialon ?? '-NO HAY TOKEN REGISTRADO-' }}</p>
          <hr class="my-1">
        </div>
        <div class="card-body">
          <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
            <thead>
              <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Servicio</th>
                <th scope="col" class="text-center">Alias</th>
                <th scope="col" class="text-center">token</th>
                <th scope="col" class="text-center">endpoint</th>
                <th scope="col" class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($servicio->repetidores as $repetidor)
                <tr class="repetidor-{{ $repetidor->id }}">
                  <td scope="row">{{ $loop->index + 1 }}</td>
                  <td>{{ $repetidor->servicio }}</td>
                  <td>{{ $repetidor->alias }}</td>
                  <td title="{{ $repetidor->token }}">{{ $repetidor->token }}</td>
                  <td title="{{ $repetidor->endpoint }}">{{ $repetidor->endpoint }}</td>
                  <td>
                    <div class="dropdown btn-config-dropdown">
                      <button class="btn dropdown-toggle btn-fill btn-sm" type="button" id="dropdownConfigLink-{{ $repetidor->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cogs"></i>
                      </button>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownConfigLink-{{ $repetidor->id }}">
                        <a class="dropdown-item" href="#"><i class="fa fa-file-text-o"></i> Logs</a>
                        <a class="dropdown-item" href="{{ route('repetidores.edit', ['repetidor' => $repetidor->id]) }}"><i class="fa fa-pencil"></i> Editar</a>
                        <a class="dropdown-item text-danger" href="#" role="button" data-repetidor="{{ $repetidor->id }}" data-toggle="modal" data-target="#deleteRepetidorModal">
                          <i class="fa fa-times" aria-hidden="true"></i>
                          Eliminar
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          <hr>
          <p class="card-category">
            {{ $servicio->created_at }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="delModalLabel">Eliminar Servicio</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-md-center">
            <form class="col-md-10" action="{{ route('servicios.destroy', ['servicio' => $servicio->id]) }}" method="POST">
              @csrf
              @method('DELETE')

              <p class="text-center">¿Esta seguro de eliminar este Servicio?</p>

              <center>
                <button class="btn btn-fill btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="deleteRepetidorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteRepetidorModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="deleteRepetidorModalLabel">Eliminar Repetidor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="text-center">¿Esta seguro de eliminar este Repetidor?</p>

            <div class="alert alert-dismissible alert-repetidores alert-danger" role="alert" style="display: none">
              <strong class="text-center">Ha ocurrido un error</strong> 

              <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <center>
              <button id="delete-repetidor" data-repetidor="" class="btn btn-fill btn-danger" type="button">Eliminar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </center>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function (){
      btnDeleteRepetidor.click(deleteRepetidor)
      $('#deleteRepetidorModal').on('show.bs.modal', function (event){
        let repetidor = $(event.relatedTarget).data('repetidor')

        btnDeleteRepetidor.data('repetidor', repetidor)
      })

      $('#deleteRepetidorModal').on('hide.bs.modal', function (){
        btnDeleteRepetidor.data('repetidor', null)
      })
    })
    const btnDeleteRepetidor = $('#delete-repetidor');

    function deleteRepetidor(){
      let repetidor = btnDeleteRepetidor.data('repetidor'),
          url = `{{ route('repetidores.index') }}/${repetidor}`;

      if(repetidor > 0){
        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _method: 'DELETE',
            _token: '{{ @csrf_token() }}',
          },
          dataType: 'json',
        })
        .done(function (response) {
          if(response === true){
            $(`.repetidor-${repetidor}`).remove()
            $('#deleteRepetidorModal').modal('hide')
          }else{
            $('.alert-repetidores').show().delay(5000).hide('slow')  
          }
        })
        .fail(function () {
          $('.alert-repetidores').show().delay(5000).hide('slow')
        })
      }else{
        $('.alert-repetidores').show().delay(5000).hide('slow')
      }
    }
  </script>
@endsection
