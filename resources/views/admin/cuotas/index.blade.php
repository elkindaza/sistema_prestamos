@extends('layouts.admin')
@section('title', 'Cuotas')

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
    <form method="GET" action="{{ route('admin.cuotas.index') }}" class="flex gap-2">
        <input
            type="number"
            name="prestamo_id"
            min="1"
            value="{{ $prestamoId ?? '' }}"
            class="border rounded px-3 py-2"
            placeholder="Filtrar por préstamo ID"
        >
        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Filtrar
        </button>

        <a href="{{ route('admin.cuotas.index') }}"
           class="px-3 py-2 bg-gray-200 text-gray-800 rounded">
            Limpiar
        </a>
    </form>

    <div class="flex gap-2">
        <a href="{{ route('admin.cuotas.create') }}"
           class="px-3 py-2 bg-blue-600 text-white rounded">
            + Nueva cuota
        </a>

     
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#E0E7FF">
            <tr>
                <th class="p-3 text-left">Préstamo</th>
                <th class="p-3 text-left">Cliente</th>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Vence</th>
                <th class="p-3 text-right">Capital</th>
                <th class="p-3 text-right">Interés</th>
                <th class="p-3 text-right">Total</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($cuotas as $cuota)
            <tr class="border-t hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-center">
                    <a class="text-blue-600"
                       href="{{ route('admin.prestamos.show', $cuota->prestamo_id) }}">
                        #{{ $cuota->prestamo_id }}
                    </a>
                </td>

                <td class="px-4 py-3 text-center">
                    {{ $cuota->prestamo?->cliente?->nombre_completo ?? '—' }}
                </td>

                <td class="px-4 py-3 text-center">{{ $cuota->numero }}</td>

                <td class="px-4 py-3 text-center">
                    {{ \Illuminate\Support\Carbon::parse($cuota->fecha_vencimiento)->format('Y-m-d') }}
                </td>

                <td class="p-3 text-right">
                    $ {{ number_format($cuota->capital_programado, 2, ',', '.') }}
                </td>

                <td class="p-3 text-right">
                    $ {{ number_format($cuota->interes_programado, 2, ',', '.') }}
                </td>

                <td class="p-3 text-right">
                    $ {{ number_format($cuota->total_programado, 2, ',', '.') }}
                </td>

                <td class="px-4 py-3 text-center">
                    @php
                        $badge = match($cuota->estado) {
                            'pagada' => 'bg-green-100 text-green-800',
                            'vencida' => 'bg-red-100 text-red-800',
                            'parcial' => 'bg-yellow-100 text-yellow-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded {{ $badge }}">
                        {{ $cuota->estado }}
                    </span>
                </td>

                <td class="p-2">
                    <div class="flex justify-center items-center gap-2">
                        <!-- EDITAR -->
                        <a href="{{ route('admin.cuotas.edit', $cuota) }}"
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
                        <a href="{{ route('admin.cuotas.show', $cuota) }}"
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
        @empty
            <tr>
                <td colspan="9" class="p-6 text-center text-gray-500">
                    No hay cuotas registradas.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $cuotas->withQueryString()->links() }}
</div>

@endsection
