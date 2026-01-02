@extends('layouts.admin')
@section('title', 'Clientes')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="flex items-center justify-between mb-4">
    <form class="flex gap-2" method="GET">
        <input
            name="q"
            value="{{ $q }}"
            class="border rounded px-3 py-2"
            placeholder="Buscar por nombre, documento o teléfono"
        >
        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Buscar
        </button>
    </form>

    <a href="{{ route('clientes.create') }}"
       class="px-3 py-2 bg-blue-600 text-white rounded">
        + Nuevo cliente
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Documento</th>
                <th class="p-3 text-left">Teléfono</th>
                <th class="p-3 text-left">Riesgo</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clientes as $c)
            <tr class="border-t">
                <td class="p-3">{{ $c->nombre_completo }}</td>
                <td class="p-3">
                    {{ $c->tipo_documento }} {{ $c->numero_documento }}
                </td>
                <td class="p-3">{{ $c->telefono }}</td>
                <td class="p-3">{{ $c->nivel_riesgo }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded bg-gray-100">
                        {{ $c->status }}
                    </span>
                </td>
                <td class="p-3">
                    <a class="text-indigo-600"
                       href="{{ route('clientes.edit', $c) }}">
                        Editar
                    </a>

                    <span class="text-gray-400 mx-1">|</span>

                    <form class="inline"
                          method="POST"
                          action="{{ route('clientes.destroy', $c) }}">
                        @csrf
                        @method('DELETE')
                        <button
                            class="text-red-600"
                            onclick="return confirm('¿Inactivar cliente?')">
                            Inactivar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $clientes->links() }}
</div>

@endsection
