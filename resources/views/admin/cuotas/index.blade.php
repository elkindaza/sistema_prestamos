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

        <a href="{{ route('admin.prestamos.index') }}"
           class="px-3 py-2 bg-gray-200 text-gray-800 rounded">
            Volver a préstamos
        </a>
    </div>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
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
            <tr class="border-t">
                <td class="p-3">
                    <a class="text-blue-600"
                       href="{{ route('admin.prestamos.show', $cuota->prestamo_id) }}">
                        #{{ $cuota->prestamo_id }}
                    </a>
                </td>

                <td class="p-3">
                    {{ $cuota->prestamo?->cliente?->nombre_completo ?? '—' }}
                </td>

                <td class="p-3">{{ $cuota->numero }}</td>

                <td class="p-3">
                    {{ \Illuminate\Support\Carbon::parse($cuota->fecha_vencimiento)->format('Y-m-d') }}
                </td>

                <td class="p-3 text-right">
                    ${{ number_format($cuota->capital_programado, 2, ',', '.') }}
                </td>

                <td class="p-3 text-right">
                    ${{ number_format($cuota->interes_programado, 2, ',', '.') }}
                </td>

                <td class="p-3 text-right font-semibold">
                    ${{ number_format($cuota->total_programado, 2, ',', '.') }}
                </td>

                <td class="p-3">
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

                <td class="p-3">
                    <a class="text-blue-600"
                       href="{{ route('admin.cuotas.show', $cuota) }}">
                        Ver
                    </a>
                    <span class="text-gray-400 mx-1">|</span>
                    <a class="text-indigo-600"
                       href="{{ route('admin.cuotas.edit', $cuota) }}">
                        Editar
                    </a>
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
