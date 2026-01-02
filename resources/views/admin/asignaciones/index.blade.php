@extends('layouts.admin')
@section('title', 'Asignaciones de Pagos')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-semibold">Asignaciones de pagos</h1>

    <a href="{{ route('admin.asignaciones.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded">
        + Nueva asignación
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Pago</th>
                <th class="p-3 text-left">Cliente</th>
                <th class="p-3 text-left">Cuota</th>
                <th class="p-3 text-right">Capital</th>
                <th class="p-3 text-right">Interés</th>
                <th class="p-3 text-right">Mora</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($asignaciones as $a)
            <tr class="border-t">
                <td class="p-3">#{{ $a->id }}</td>
                <td class="p-3">Pago #{{ $a->pago_id }}</td>
                <td class="p-3">
                    {{ $a->pago->prestamo->cliente->nombre_completo ?? '—' }}
                </td>
                <td class="p-3">Cuota {{ $a->cuota->numero }}</td>

                <td class="p-3 text-right">${{ number_format($a->capital_pagado, 0, ',', '.') }}</td>
                <td class="p-3 text-right">${{ number_format($a->intereses_pagado, 0, ',', '.') }}</td>
                <td class="p-3 text-right">${{ number_format($a->mora_pagada, 0, ',', '.') }}</td>

                <td class="p-3">{{ $a->asignado_en }}</td>

                <td class="p-3 space-x-2">
                    <a href="{{ route('admin.asignaciones.show', $a) }}" class="text-blue-600">Ver</a>
                    <a href="{{ route('admin.asignaciones.edit', $a) }}" class="text-indigo-600">Editar</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $asignaciones->links() }}
</div>

@endsection
