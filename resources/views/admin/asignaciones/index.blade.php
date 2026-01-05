@extends('layouts.admin')
@section('title', 'Asignaciones de Pagos')

@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
    {{ session('success') }}
</div>
@endif

<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-semibold"></h1>

    <a href="{{ route('admin.asignaciones.create') }}"
        class="px-4 py-2 bg-blue-600 text-white rounded">
        + Nueva asignación
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#E0E7FF">
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
            <tr class="border-t hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-center">#{{ $a->id }}</td>
                <td class="px-4 py-3 text-center">Pago #{{ $a->pago_id }}</td>
                <td class="px-4 py-3 text-center">
                    {{ $a->pago->prestamo->cliente->nombre_completo ?? '—' }}
                </td>
                <td class="px-4 py-3 text-center">Cuota {{ $a->cuota->numero }}</td>

                <td class="p-3 text-right">${{ number_format($a->capital_pagado, 0, ',', '.') }}</td>
                <td class="p-3 text-right">${{ number_format($a->intereses_pagado, 0, ',', '.') }}</td>
                <td class="p-3 text-right">${{ number_format($a->mora_pagada, 0, ',', '.') }}</td>

                <td class="px-4 py-3 text-center">{{ $a->asignado_en }}</td>

                <td class="p-2">
                    <div class="flex justify-center items-center gap-2">
                        <!-- EDITAR -->
                        <a href="{{ route('admin.asignaciones.edit', $a) }}"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition"
                            style="background:#E5E7EB;color:#374151"
                            onmouseover="this.style.background='#D1D5DB'"
                            onmouseout="this.style.background='#E5E7EB'">

                            <!-- ICONO EDITAR -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5h2m-1 0v14m-7-7h14" />
                            </svg>

                            Editar
                        </a>
                        <a href="{{ route('admin.asignaciones.show', $a) }}"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition"
                            style="background:#DCFCE7;color:#166534"
                            onmouseover="this.style.background='#D1D5DB'"
                            onmouseout="this.style.background='#E5E7EB'">

                            <!-- ICONO ver -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5
             c4.478 0 8.268 2.943 9.542 7
             -1.274 4.057-5.064 7-9.542 7
             -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver
                        </a>
                    </div>

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