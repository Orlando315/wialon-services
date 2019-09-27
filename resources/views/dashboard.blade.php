@extends('layouts.app')

@section('title', 'Inicio - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Inicio </a>
@endsection

@section('content')
  <div class="row justify-content-md-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title text-center">Token Wialon</h5>
        </div>
        <div class="card-body">
          <p class="card-text {{ Auth::user()->token->wialon ? 'badge-primary' : 'badge-secondary' }} text-center token-wialon rounded">
            {{ Auth::user()->token->wialon ?? 'No ha iniciado sesión o ha ocurrido un error con el token de Wialon' }}
          </p>

          <button id="login-wialon" class="btn btn-primary btn-fill btn-sm{{ Auth::user()->token->wialon ? ' d-none' : '' }}" type="button"{{ Auth::user()->token->wialon ? ' disabled' : '' }}>
            Login con Wialon
          </button>

          <button id="remove-wialon" class="btn btn-danger btn-fill btn-sm{{ Auth::user()->token->wialon ? '' : ' d-none' }}" type="button"{{ Auth::user()->token->wialon ? '' : ' disabled' }}>
            Eliminar token
          </button>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5 class="card-title text-center">Token Wisetrack</h5>
        </div>
        <div class="card-body">
          <form id="form-wisetrack" action="{{ route('tokens.store') }}" method="POST">
            <input type="hidden" name="field" value="wisetrack">
            <div class="form-group">
              <input id="wisetrack" class="form-control" type="text" name="wisetrack" maxlength="80" value="{{ Auth::user()->token->wisetrack }}">
            </div>
            <button id="submit-wisetrack" type="submit" class="btn btn-primary btn-fill btn-sm">Guardar</button>
          </form>
        </div>
      </div>

      <div class="alert alert-dismissible alert-token" role="alert" style="display: none">
        <strong id="alert-message" class="text-center"></strong>

        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
  
  @include('partials.flash')
@endsection

@section('scripts')
  <script type="text/javascript" src="//hst-api.wialon.com/wsdk/script/wialon.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#login-wialon').click(getToken)
      $('#form-wisetrack').submit(storeToken)
      $('#remove-wialon').click(removeWialon)
    })

    const alert = $('.alert-token');
    let dns = 'http://gps.epol.cl';
    // Main function
    function getToken() {
      // construct login page URL
      let url = dns + '/login.html'; // your site DNS + "/login.html"
      url += '?client_id=' + 'App'; // your application name
      url += '&access_type=' + 0x100; // access level, 0x100 = "Online tracking only"
      url += '&activation_time=' + 0; // activation time, 0 = immediately; you can pass any UNIX time value
      url += '&duration=' + 2592000; // duration, 2592000 = one month in seconds
      url += '&flags=' + 0x1;     // options, 0x1 = add username in response
      url += `&redirect_uri=${dns}/post_token.html`; // if login succeed - redirect to this page
      
      window.addEventListener('message', tokenRecieved);
      window.open(url, '_blank', 'width=760, height=500, top=300, left=500');    
    }

    // Help function
    function tokenRecieved(e) {
      let msg = e.data;
      if (typeof msg == 'string' && msg.indexOf('access_token=') >= 0) {
        let token = msg.replace('access_token=', '');
        console.log(token)
        wialon.core.Session.getInstance().initSession('https://hst-api.wialon.com');
        
        wialon.core.Session.getInstance().loginToken(token, '', function(code) {
          if(code){
            showAlert()
            console.log(`Error: ${wialon.core.Errors.getErrorText(code)}`)
            return;
          }

          storeToken(null, token)
        });
      }
    }

    function storeToken(event, token = null) {
      if(token){
        wialonLogout()
      }else{
        event.preventDefault()
        $('#submit-wisetrack').prop('disabled', true)
      }

      let field = token ? 'wialon' : 'wisetrack'

      $.ajax({
        type: 'POST',
        url: '{{ route("tokens.store") }}',
        data: {
          _token: '{{ @csrf_token() }}',
          field: field,
          token: token || $('#wisetrack').val(),
        },
        dataType: 'json'
      })
      .done(function (data) {
        if(data.response){
          if(field == 'wialon'){
            $('.token-wialon').toggleClass('badge-primary badge-secondary').text(token)
            toggleButton('login-wialon', true)
            toggleButton('remove-wialon', false)
          }
        }

        let message = data.response ? 'Token guardado con exito.' : null

        showAlert(message, !data.response)
      })
      .fail(function () {
        showAlert()
      })
      .always(function () {
        $('#submit-wisetrack').prop('disabled', false)
      })
    }

    function wialonLogout() {
      wialon.core.Session.getInstance().logout(
        function (code) {
          if(code){
            showAlert(`Ha ocurrido un error: ${code}`)
            console.log('Logout error: ', code);
          }
        }
      );
    }

    function showAlert(message = null, error = true) {
      alert
        .toggleClass('alert-danger', error)
        .toggleClass('alert-success', !error)

      alert
        .find('#alert-message')
        .text(message || 'Ha ocurrido un error')

      alert.show().delay(5000).hide('slow')
    }

    function removeWialon() {
      $.ajax({
        type: 'POST',
        url: '{{ route("tokens.nullify") }}',
        data: {
          _token: '{{ @csrf_token() }}',
        },
        dataType: 'json'
      })
      .done(function (data) {
        if(data.response){
          $('.token-wialon')
            .toggleClass('badge-primary badge-secondary')
            .text('No ha iniciado sesión o ha ocurrido un error con el token de Wialon')

          toggleButton('login-wialon', false)
          toggleButton('remove-wialon', true)
        }else{
          showAlert()
        }
      })
      .fail(function () {
        showAlert()
      })
    }

    function toggleButton(id, disable) {
      $(`#${id}`).toggleClass('d-none', disable).prop('disabled', disable)
    }
  </script>
@endsection
