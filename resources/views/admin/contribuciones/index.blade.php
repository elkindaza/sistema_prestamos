@extends('layouts.admin')
@section('title', 'Contribuciones')

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

<div class="flex items-start justify-between mb-4 gap-4">
    <form class="flex flex-wrap gap-2" method="GET">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            class="border rounded px-3 py-2"
            placeholder="Buscar: id, referencia, nombre, email"
        >

        <input
            type="date"
            name="desde"
            value="{{ $desde }}"
            class="border rounded px-3 py-2"
        >

        <input
            type="date"
            name="hasta"
            value="{{ $hasta }}"
            class="border rounded px-3 py-2"
        >

        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Filtrar
        </button>

        <a href="{{ route('admin.contribuciones.index') }}"
           class="px-3 py-2 bg-gray-200 text-gray-800 rounded">
            Limpiar
        </a>
    </form>

    <a href="{{ route('admin.contribuciones.create') }}"
       class="px-3 py-2 bg-blue-600 text-white rounded whitespace-nowrap">
        + Nueva contribución
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Asociado</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-right">Monto</th>
                <th class="p-3 text-left">Método</th>
                <th class="p-3 text-left">Referencia</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contribuciones as $c)
            <tr class="border-t">
                <td class="p-3">#{{ $c->id }}</td>

                <td class="p-3">
                    <div class="font-medium">
                        {{ $c->asociado?->user?->nombre ?? '—' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $c->asociado?->user?->email ?? '' }}
                    </div>
                </td>

                <td class="p-3">
                    {{ optional($c->aportado_en)->format('Y-m-d H:i') }}
                </td>

                <td class="p-3 text-right font-semibold">
                    ${{ number_format((float)$c->monto, 2, ',', '.') }}
                </td>

                <td class="p-3">{{ $c->metodo }}</td>

                <td class="p-3">{{ $c->referencia ?? '—' }}</td>

                <td class="p-3">
                    <a class="text-blue-600"
                       href="{{ route('admin.contribuciones.show', $c) }}">
                        Ver
                    </a>
                    <span class="text-gray-400 mx-1">|</span>
                    <a class="text-indigo-600"
                       href="{{ route('admin.contribuciones.edit', $c) }}">
                        Editar
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-6 text-center text-gray-500">
                    No hay contribuciones.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $contribuciones->links() }}
</div>

@endsection
