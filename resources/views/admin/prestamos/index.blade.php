@extends('layouts.admin')
@section('title', 'Préstamos')

@section('content')
@if(session('success')) <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div> @endif
@if(session('error')) <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">{{ session('error') }}</div> @endif

<div class="flex items-center justify-between mb-4">
    <form class="flex gap-2" method="GET">
        <input name="q" value="{{ $q }}" class="border rounded px-3 py-2" placeholder="Buscar por cliente/estado/id">
        <button class="px-3 py-2 bg-gray-800 text-white rounded">Buscar</button>
    </form>

    <a href="{{ route('admin.prestamos.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">
        + Nuevo préstamo
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Cliente</th>
                <th class="p-3 text-left">Monto</th>
                <th class="p-3 text-left">Plazo</th>
                <th class="p-3 text-left">Tasa</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($prestamos as $p)
            <tr class="border-t">
                <td class="p-3">#{{ $p->id }}</td>
                <td class="p-3">{{ $p->cliente?->nombre_completo }}</td>
                <td class="p-3">${{ number_format($p->monto_principal, 0, ',', '.') }}</td>
                <td class="p-3">{{ $p->meses_plazo }} meses</td>
                <td class="p-3">{{ $p->tasa_interes }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded bg-gray-100">{{ $p->estado }}</span>
                </td>
                <td class="p-3">
                    <a class="text-blue-600" href="{{ route('admin.prestamos.show', $p) }}">Ver</a>
                    <span class="text-gray-400 mx-1">|</span>
                    <a class="text-indigo-600" href="{{ route('admin.prestamos.edit', $p) }}">Editar</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $prestamos->links() }}
</div>
@endsection
