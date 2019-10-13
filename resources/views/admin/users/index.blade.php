@extends('layouts.app')

@section('title', 'Usuarios - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('admin.users.index') }}"> Usuarios </a>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="card card-stats">
        <div class="card-body ">
          <div class="row">
            <div class="col-5">
              <div class="icon-big text-center icon-warning">
                <i class="fa fa-users text-primary"></i>
              </div>
            </div>
            <div class="col-7">
              <div class="numbers">
                <p class="card-category">Usuarios</p>
                <h4 class="card-title">{{ $users->count() }}</h4>
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
            <i class="fa fa-users"></i> Usuarios
            <span class="float-right">
              <a class="btn btn-success" href="{{ route('admin.users.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Usuario</a>
            </span>
          </h4>
        </div>
        <div class="card-body">
          <table class="table data-table table-striped table-bordered table-hover table-sm" style="width: 100%">
            <thead>
              <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Nombres</th>
                <th scope="col" class="text-center">Apellidos</th>
                <th scope="col" class="text-center">Email</th>
                <th scope="col" class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($users as $d)
                <tr>
                  <td scope="row">{{ $loop->index + 1 }}</td>
                  <td>{{ $d->nombres }}</td>
                  <td>{{ $d->apellidos }}</td>
                  <td>{{ $d->email }}</td>
                  <td>
                    <a class="btn btn-primary btn-link btn-sm" href="{{ route('admin.users.show', ['id' => $d->id] )}}" rel="tooltip" title="Ver usuario" data-original-title="Ver usuario">
                      <i class="fa fa-search"></i>
                    </a>
                    <a class="btn btn-success btn-link btn-sm" href="{{ route('admin.users.edit', ['id' => $d->id] )}}" rel="tooltip" title="Editar usuario" data-original-title="Editar usuario">
                      <i class="fa fa-pencil"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            <i class="fa fa-users"></i> Administradores
          </h4>
        </div>
        <div class="card-body">
          <table class="table data-table table-striped table-bordered table-hover table-sm" style="width: 100%">
            <thead>
              <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Nombres</th>
                <th scope="col" class="text-center">Apellidos</th>
                <th scope="col" class="text-center">Email</th>
                <th scope="col" class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($admins as $d)
                <tr>
                  <td scope="row">{{ $loop->index + 1 }}</td>
                  <td>{{ $d->nombres }}</td>
                  <td>{{ $d->apellidos }}</td>
                  <td>{{ $d->email }}</td>
                  <td>
                    <a class="btn btn-primary btn-link btn-sm" href="{{ route('admin.users.show', ['id' => $d->id] )}}" rel="tooltip" title="Ver usuario" data-original-title="Ver usuario">
                      <i class="fa fa-search"></i>
                    </a>
                    <a class="btn btn-success btn-link btn-sm" href="{{ route('admin.users.edit', ['id' => $d->id] )}}" rel="tooltip" title="Editar usuario" data-original-title="Editar usuario">
                      <i class="fa fa-pencil"></i>
                    </a>
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
