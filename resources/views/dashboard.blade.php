@extends('layouts.app')

@section('title', 'Inicio - '.config('app.name'))

@section('brand')
  <a class="navbar-brand" href="{{ route('dashboard') }}"> Inicio </a>
@endsection

@section('content')
  <div class="row">
  </div>
  
  @include('partials.flash')
@endsection
