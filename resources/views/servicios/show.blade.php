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
      <div class="card card-servicio card-dropdown-tabs">
        <div class="card-header">
          <h4 class="card-title">
            {{ $servicio->alias ?? 'Servicio' }}
            <a class="btn btn-primary btn-fill btn-xs" href="{{ route('repetidores.create', ['servicio' => $servicio->id]) }}" title="Agregar repetidor">
              <i class="fa fa-plus"></i> Agregar repetidor
            </a>
            <button class="btn btn-secondary btn-fill btn-xs btn-load-logs" type="button" data-servicio="{{ $servicio->id }}">
              <i class="fa fa-file-text-o"></i>
              Logs
            </button>
          </h4>
          <p class="card-category{{ $servicio->wialon ? '' : ' text-danger' }}">Token: {{ $servicio->wialon ?? '-NO HAY TOKEN REGISTRADO-' }}</p>
          <p class="card-category{{ $servicio->isAboutToExpire() ? ' text-danger' : '' }}">Fecha de expiración aproximada: {{ $servicio->wialon_expiration ?? '-' }}</p>
          <hr class="my-1">
        </div>
        <div class="card-body">
          <ul id="repetidores-tokens" class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="repetidores-tab" href="#repetidores" role="tab" data-toggle="tab" aria-controls="repetidores" aria-selected="true"><i class="fa fa-podcast"></i> Repetidores</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="api-tab" href="#api" role="tab" data-toggle="tab" aria-controls="api" aria-selected="false"><i class="fa fa-cubes"></i> API Tokens</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="repetidores" class="tab-pane fade show active" role="tabpanel" aria-labelledby="repetidores-tab">
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
                <tbody class="">
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
                            <a class="dropdown-item btn-load-logs" href="#" data-repetidor="{{ $repetidor->id }}"><i class="fa fa-file-text-o"></i> Logs</a>
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
            <div id="api" class="tab-pane fade" role="tabpanel" aria-labelledby="api-tab">
              <button class="btn btn-primary btn-fill btn-xs btn-generate-token my-2" title="Generar token">
                <i class="fa fa-plus"></i> Generar token
              </button>

              <p class="card-category">Url: <span class="span-copy badge badge-primary" data-toggle="tooltip" title="Haz click para copiar!">{{ route('api.servicios.getData') }}</span></p>
              <p class="card-category">El token debe ser enviado en el header de la petición con el key 'Authorization'</p>

              <div class="alert alert-dismissible alert-tab-tokens alert-danger" role="alert" style="display: none">
                <strong class="text-center">Ha ocurrido un error</strong> 

                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
                <thead>
                  <tr>
                    <th scope="col" class="text-center">Creado</th>
                    <th scope="col" class="text-center">Token</th>
                    <th scope="col" class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="tbody-tokens">
                  @foreach(Auth::user()->tokens as $token)
                    <tr class="token-{{ $token->id }}">
                      <td scope="row">{{ $token->created_at }}</td>
                      <td class="{{ $token->api_token }}">
                        <span class="span-copy badge badge-secondary" data-toggle="tooltip" title="Haz click para copiar!">{{ $token->token }}</span>
                      </td>
                      <td>
                        <div class="dropdown btn-config-dropdown">
                          <button class="btn dropdown-toggle btn-fill btn-sm" type="button" id="dropdownApiLink-{{ $token->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cogs"></i>
                          </button>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownApiLink-{{ $token->id }}">
                            <a class="dropdown-item text-danger" href="#" role="button" data-token="{{ $token->id }}" data-toggle="modal" data-target="#deleteTokenModal">
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
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-dismissible alert-danger alert-logs" role="alert" style="display: none">
        <strong class="text-center">Ha ocurrido un error al cargar los Logs.</strong> 

        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card card-logs">
        <div class="card-header">
          <h4 class="card-title">
            <i class="fa fa-file-text-o"></i> Logs
            <button class="btn btn-success btn-fill btn-xs btn-reload-logs" type="button" title="Agregar repetidor">
              <i class="fa fa-refresh"></i> Recargar
            </button>
          </h4>
        </div><!-- .card-header -->
        <div class="card-body content-full-width">
          <ul role="tablist" class="nav nav-tabs">
            <li role="presentation" class="nav-item" aria-expanded="true">
              <a class="nav-link active" id="all-tab" href="#logs-all" data-toggle="tab" aria-expanded="true"><i class="fa fa-info"></i> Todos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="success-tab" href="#logs-success" data-toggle="tab"><i class="fa fa-check"></i> Completos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="error-tab" href="#logs-error" data-toggle="tab"><i class="fa fa-close"></i> Error</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="logs-all" class="tab-pane fade active show" role="tabpanel" aria-labelledby="all-tab" aria-expanded="true">
              <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">Fecha</th>
                      <th scope="col" class="text-center">Error</th>
                      <th scope="col" class="text-center">Código</th>
                      <th scope="col" class="text-center">Mensaje</th>
                      <th scope="col" class="text-center">Token</th>
                    </tr>
                  </thead>
                  <tbody class="tbody-logs-all">
                    @foreach($logs->all as $all)
                      <tr>
                        <td scope="row">{{ $all->created_at }}</td>
                        <td class="text-center">{!! $all->type() !!}</td>
                        <td class="text-center">{{ $all->code }}</td>
                        <td>{{ $all->message }}</td>
                        <td>{{ $all->token }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div id="logs-success" class="tab-pane fade" role="tabpanel" aria-labelledby="success-tab" aria-expanded="false">
              <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">Fecha</th>
                      <th scope="col" class="text-center">Mensaje</th>
                      <th scope="col" class="text-center">Token</th>
                    </tr>
                  </thead>
                  <tbody class="tbody-logs-success">
                    @foreach($logs->success as $success)
                      <tr>
                        <td scope="row">{{ $success->created_at }}</td>
                        <td>{{ $success->message }}</td>
                        <td>{{ $success->token }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div id="logs-error" class="tab-pane fade" role="tabpanel" aria-labelledby="error-tab" aria-expanded="false">
              <div class="table-responsive">
                <table class="table table-sm table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">Fecha</th>
                      <th scope="col" class="text-center">Código</th>
                      <th scope="col" class="text-center">Mensaje</th>
                      <th scope="col" class="text-center">Token</th>
                    </tr>
                  </thead>
                  <tbody class="tbody-logs-error">
                    @foreach($logs->error as $error)
                      <tr>
                        <td scope="row">{{ $error->created_at }}</td>
                        <td class="text-center">{{ $error->code }}</td>
                        <td>{{ $error->message }}</td>
                        <td>{{ $error->token }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div><!-- .card-body --->
        <div class="card-loading justify-content-center">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
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

  <div id="deleteTokenModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteTokenModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="deleteTokenodalLabel">Eliminar Token</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="text-center">¿Esta seguro de eliminar este Token?</p>

            <div class="alert alert-dismissible alert-tokens alert-danger" role="alert" style="display: none">
              <strong class="text-center">Ha ocurrido un error</strong> 

              <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <center>
              <button id="delete-token" data-token="" class="btn btn-fill btn-danger" type="button">Eliminar</button>
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
      btnDeleteToken.click(deleteToken)
      btnGenerateToken.click(generateToken)

      $('#deleteRepetidorModal').on('show.bs.modal', function (event){
        let repetidor = $(event.relatedTarget).data('repetidor')

        btnDeleteRepetidor.data('repetidor', repetidor)
      })

      $('#deleteRepetidorModal').on('hide.bs.modal', function (){
        btnDeleteRepetidor.data('repetidor', null)
      })

      $('#deleteTokenModal').on('show.bs.modal', function (event){
        let token = $(event.relatedTarget).data('token')

        btnDeleteToken.data('token', token)
      })

      $('#deleteTokenModal').on('hide.bs.modal', function (){
        btnDeleteToken.data('token', null)
      })

      $('.btn-load-logs').click(function (event) {
        event.preventDefault();

        selectedLogs = $(this).data('repetidor') || $(this).data('servicio')
        logsType     = $(this).data('repetidor') ? 'repetidor' : 'servicio';

        loadLogs()
      })

      $('.btn-reload-logs').click(loadLogs)

      $('#api').on('click', '.span-copy', copyToClipboard)
      $('.span-copy').tooltip()
      $('.span-copy').on('hide.bs.tooltip', function() {
        $('.span-copy')
          .attr('data-original-title', 'Haz click para copiar!')
      })
    })

    const cardLoading = $('.card-loading'),
          btnDeleteRepetidor = $('#delete-repetidor'),
          btnDeleteToken = $('#delete-token'),
          btnGenerateToken = $('.btn-generate-token'),
          alertLogs = $('.alert-logs');

    let selectedLogs = @json($logs->id),
        logsType     = '{{ $logs->type }}';

    function deleteRepetidor(){
      let repetidor = btnDeleteRepetidor.data('repetidor'),
          url = `{{ route('repetidores.index') }}/${repetidor}`;

      btnDeleteRepetidor.prop('disabled', true)

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
        .always(function () {
          btnDeleteRepetidor.prop('disabled', false)
        })
      }else{
        $('.alert-repetidores').show().delay(5000).hide('slow')
        btnDeleteRepetidor.prop('disabled', false)
      }
    }

    function deleteToken(){
      let token = btnDeleteToken.data('token'),
          url = `{{ route('tokens.index') }}/${token}`;

      btnDeleteToken.prop('disabled', true)

      if(token > 0){
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
            $(`.token-${token}`).remove()
            $('#deleteTokenModal').modal('hide')
          }else{
            $('.alert-tokens').show().delay(5000).hide('slow')  
          }
        })
        .fail(function () {
          $('.alert-tokens').show().delay(5000).hide('slow')
        })
        .always(function () {
          btnDeleteToken.prop('disabled', false)
        })
      }else{
        $('.alert-tokens').show().delay(5000).hide('slow')
        btnDeleteToken.prop('disabled', false)
      }
    }

    function loadLogs(event){
      let url = `{{ route("logs.index") }}/${selectedLogs}/${logsType}`;
      $('tbody[class^="tbody-logs"]').empty()

      toggleLogsLoading()

      $.ajax({
        type: 'POST',
        url: url,
        data: {
          _method: 'POST',
          _token: '{{ @csrf_token() }}',
        },
        dataType: 'json'
      })
      .done(function (response) {
        $.each(response, function (table, data){
          let tbody = '';

          $.each(data, function (i, log){

            tbody += '<tr>'
            tbody += `<td>${log.date}</td>`

            if(table == 'all'){
              tbody += `<td class="text-center">${log.error}</td>`
            }

            if(table == 'all' || table == 'error'){
              tbody += `<td class="text-center">${log.code}</td>`
            }
            
            tbody += `<td>${log.message}</td>`
            tbody += `<td>${log.token}</td>`
            tbody += '</tr>'
          })

          $(`.tbody-logs-${table}`).append(tbody)
        })
      })
      .fail(function () {
        alertLogs.show().delay(5000).hide('slow')
      })
      .always(function () {
        toggleLogsLoading(false)
      })
    }

    function toggleLogsLoading(show = true){
      $('.btn-reload-logs, .btn-load-logs').prop('disable', show)
      cardLoading.toggleClass('d-flex', show)
      show ? cardLoading.fadeIn() : cardLoading.fadeOut()
    }

    function generateToken(){
      let url = `{{ route("tokens.store") }}`;
      let alertTableToken = $('alert-tab-tokens');

      btnGenerateToken.prop('disabled', true)

      $.ajax({
        type: 'POST',
        url: url,
        data: {
          _method: 'POST',
          _token: '{{ @csrf_token() }}',
          servicio: @json($servicio->id),
        },
        dataType: 'json'
      })
      .done(function (response) {
        if(response){
          let tbody = $('.tbody-tokens')
          let trToken = tokenTemplate(response)
          tbody.append(trToken)

          $('.span-copy').tooltip()
        }else{
          alertTableToken.show().delay(5000).hide('slow')
        }
      })
      .fail(function () {
        alertTableToken.show().delay(5000).hide('slow')
      })
      .always(function () {
        btnGenerateToken.prop('disabled', false)
      })
    }

    let tokenTemplate = function (token){
      return `<tr class="token-${token.id}">
                <td scope="row">${token.created}</td>
                <td title="${token.token}"><span class="span-copy badge badge-secondary" data-toggle="tooltip" title="Haz click para copiar!">${token.token}</span></td>
                <td>
                  <div class="dropdown btn-config-dropdown">
                    <button class="btn dropdown-toggle btn-fill btn-sm" type="button" id="dropdownApiLink-${token.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-cogs"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownApiLink-${token.id}">
                      <a class="dropdown-item text-danger" href="#" role="button" data-token="${token.id}" data-toggle="modal" data-target="#deleteTokenModal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                        Eliminar
                      </a>
                    </div>
                  </div>
                </td>
              </tr>`;
    }

    function copyToClipboard() {
      let $temp = $('<input>');
      $('body').append($temp);
      $temp.val($(this).text()).select();
      document.execCommand('copy')
      $temp.remove();
      $(this)
        .attr('data-original-title', 'Copiado!')
        .tooltip('show')
    }
  </script>
@endsection
