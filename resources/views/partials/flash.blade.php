@if(Session::has('flash_message'))
  <div class="row justify-content-md-center mt-2">
    <div class="col-md-6">
      <div class="alert alert-dismissible {{ Session::get('flash_class') }} {{ Session::has('flash_important') ? 'alert-important' : '' }}" role="alert">
        <strong class="text-center">{{ Session::get('flash_message') }}</strong> 

        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
@endif
