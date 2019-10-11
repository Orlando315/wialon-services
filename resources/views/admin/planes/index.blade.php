@extends('layouts.app')

@section('title', 'Planes - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.planes.index') }}"> Planes </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center icon-warning">
                <i class="fa fa-list-ul text-primary"></i>
              </div>
            </div>
            <div class="col-7">
              <div class="numbers">
                <p class="card-category">Planes</p>
                <h4 class="card-title">{{ $planes->count() }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            <i class="fa fa-list-ul"></i> Planes
            <span class="float-right">
              <a class="btn btn-success" href="{{ route('admin.planes.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Plan</a>
            </span>
          </h4>
        </div>
        <div class="card-body">
          <table class="table data-table table-striped table-no-bordered table-hover table-sm" style="width: 100%">
            <thead>
              <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Precio</th>
                <th scope="col" class="text-center">Servicios</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col" class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($planes as $d)
                <tr>
                  <td scope="row">{{ $loop->index + 1 }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->precio() }}</td>
                  <td>{{ $d->servicios->count() }}</td>
                  <td>{!! $d->status() !!}</td>
                  <td>
                    <a class="btn btn-primary btn-link btn-sm" href="{{ route('admin.planes.show', ['id' => $d->id] )}}" rel="tooltip" title="Ver plan" data-original-title="Ver plan">
                      <i class="fa fa-search"></i>
                    </a>
                    @if(!$d->deteled_at)
                      <a class="btn btn-success btn-link btn-sm" href="{{ route('admin.planes.edit', ['id' => $d->id] )}}" rel="tooltip" title="Editar plan" data-original-title="Editar plan">
                        <i class="fa fa-pencil"></i>
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
