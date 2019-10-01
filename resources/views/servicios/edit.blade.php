@extends('layouts.app')

@section('title','Servicios - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Servicios </a>
@endsection

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('servicios.update', ['servicio' => $servicio->id]) }}" method="POST">
              @csrf
              @method('PATCH')

              <h4>Editar servicio</h4>

              <div class="form-group">
                <label class="control-label" for="alias">Alias:</label>
                <input id="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" type="text" name="alias" maxlength="50" value="{{ old('alias') ?? $servicio->alias }}" placeholder="Alias del servicio">
              </div>

              <div class="form-group">
                <label class="control-label" for="token">Token Wialon: *</label>
                <input id="token" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" type="text" name="token" maxlength="80" value="{{ old('token') ?? $servicio->wialon }}" placeholder="Token Wialon" readonly>
              </div>

              <div class="form-group">
                <button id="login-wialon" class="btn btn-primary btn-fill btn-block{{ $servicio->wialon || old('token') ? ' d-none' : '' }}" type="button"{{ $servicio->wialon || old('token') ? ' disabled' : '' }}>
                  Login con Wialon
                </button>

                <button id="remove-wialon" class="btn btn-danger btn-fill btn-sm{{ $servicio->wialon || old('token') ? '' : ' d-none' }}" type="button"{{ $servicio->wialon || old('token') ? '' : ' disabled' }}>
                  Eliminar token
                </button>
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
                <a class="btn btn-default" href="{{ route('dashboard') }}"><i class="fa fa-reply"></i> Atras</a>
                <button id="send-form" class="btn btn-primary" type="submit" {{ $servicio->wialon || old('token') ? '' : 'disabled' }}><i class="fa fa-send"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script type="text/javascript" src="//hst-api.wialon.com/wsdk/script/wialon.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#login-wialon').click(getToken)
      $('#remove-wialon').click(function (){
        toggleToken()
      })
    })

    const alert = $('.alert-token');
    const sendBtn = $('#send-form');
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

          toggleToken(true, token)
          wialonLogout()
        });
      }
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

    function toggleToken(isAdded = false, token = null){
      console.log(isAdded, token)
      $('#token').val(token)
      sendBtn.prop('disabled', !isAdded);
      toggleButton('login-wialon', isAdded)
      toggleButton('remove-wialon', !isAdded)
    }

    function toggleButton(id, disable) {
      $(`#${id}`).toggleClass('d-none', disable).prop('disabled', disable)
    }
  </script>
@endsection
