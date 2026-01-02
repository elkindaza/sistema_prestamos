@extends('layouts.admin')
@section('title', 'Detalle asignación')

@section('content')

<h1 class="text-xl font-semibold mb-4">
    Asignación #{{ $asignacion->id }}
</h1>

<div class="bg-white rounded shadow p-6 space-y-4 max-w-xl">

    <p><b>Pago:</b> #{{ $asignacion->pago_id }}</p>
    <p><b>Cliente:</b> {{ $asignacion->pago->prestamo->cliente->nombre_completo }}</p>
    <p><b>Cuota:</b> {{ $asignacion->cuota->numero }}</p>

    <hr>

    <p><b>Capital:</b> ${{ number_format($asignacion->capital_pagado,0,',','.') }}</p>
    <p><b>Interés:</b> ${{ number_format($asignacion->intereses_pagado,0,',','.') }}</p>
    <p><b>Mora:</b> ${{ number_format($asignacion->mora_pagada,0,',','.') }}</p>

    <p class="text-sm text-gray-500">
        Asignado en: {{ $asignacion->asignado_en }}
    </p>

    <div class="mt-4">
        <a href="{{ route('admin.asignaciones.index') }}"
           class="px-4 py-2 bg-gray-200 rounded">Volver</a>
    </div>
</div>

@endsection
