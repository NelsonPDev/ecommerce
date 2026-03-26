@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-3xl font-bold mb-6">Dashboard de Empleado</h2>
                <p class="text-lg text-gray-700">Usuario: <strong>{{ Auth::user()->name }}</strong></p>
                <p class="text-lg text-gray-700">Rol: <strong>{{ Auth::user()->role }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
