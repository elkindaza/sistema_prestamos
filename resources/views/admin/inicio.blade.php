@extends('layouts.admin')

@section('title', 'Inicio Admin')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <a href="{{ route('clientes.index') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Clientes</h3>
        <p class="text-sm text-gray-600">Crear, editar, inactivar</p>
    </a>

    <a href="{{ route('admin.prestamos.index') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Préstamos</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>

    <a href="{{ route('admin.cuotas.index') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Cuotas</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>
</div>
@endsection