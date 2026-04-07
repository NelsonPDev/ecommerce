@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Dashboard - {{ auth()->user()->rol }}</h1>
            <p>Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</p>

            @if(auth()->user()->rol === 'administrador')
                <div class="alert alert-info">
                    <h4>Panel de Administrador</h4>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Gestionar Usuarios</a>
                    <a href="{{ route('productos.create') }}" class="btn btn-success">Crear Producto</a>
                </div>
            @elseif(auth()->user()->rol === 'gerente')
                <div class="alert alert-warning">
                    <h4>Panel de Gerente</h4>
                    <a href="{{ route('productos.create') }}" class="btn btn-success">Crear Producto</a>
                </div>
            @else
                <div class="alert alert-success">
                    <h4>Panel de Cliente</h4>
                    <a href="{{ route('productos.index') }}" class="btn btn-primary">Ver Productos</a>
                    <a href="{{ route('ventas.index') }}" class="btn btn-info">Mis Compras</a>
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection
