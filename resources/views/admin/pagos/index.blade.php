@extends('layouts.admin')
@section('title', 'Pagos')

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
    {{-- Buscador --}}
    <form method="GET" class="flex gap-2">
        <input
            name="q"
            value="{{ request('q') }}"
            class="border rounded px-3 py-2"
            placeholder="Buscar por cliente o préstamo"
        >
        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Buscar
        </button>
    </form>

    <a href="{{ route('admin.pagos.create') }}"
       class="px-3 py-2 bg-blue-600 text-white rounded">
        + Nuevo pago
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Cliente</th>
                <th class="p-3 text-left">Cuota(s)</th>
                <th class="p-3 text-right">Monto</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody>
        @forelse($pagos as $pago)
            <tr class="border-t">
                {{-- ID --}}
                <td class="p-3 font-semibold">#{{ $pago->id }}</td>

                {{-- Cliente --}}
                <td class="p-3">
                    {{ $pago->prestamo?->cliente?->nombre_completo ?? '—' }}
                </td>

                {{-- Cuotas asignadas --}}
                <td class="p-3 text-xs">
                    @if($pago->asignaciones && $pago->asignaciones->count())
                        @foreach($pago->asignaciones as $asignaciones)
                            <div class="mb-1">
                                <span class="block">Cuota #{{ $asignaciones->numero }}</span>
                                <span class="text-gray-500">
                                    (ID {{ $asignaciones->id }})
                                </span>
                            </div>
                        @endforeach
                    @else
                        <span class="text-gray-400 italic">Sin asignación</span>
                    @endif
                </td>

                {{-- Monto --}}
                <td class="p-3 text-right font-semibold">
                    ${{ number_format($pago->monto, 0, ',', '.') }}
                </td>

                {{-- Estado --}}
                <td class="p-3">
                    @php
                        $badge = $pago->estado === 'registrado'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                        {{ $pago->estado }}
                    </span>
                </td>

                {{-- Fecha --}}
                <td class="p-3 text-xs text-gray-600">
                    {{ \Carbon\Carbon::parse($pago->pagado_en)->format('Y-m-d H:i') }}
                </td>

                {{-- Acciones --}}
                <td class="p-3">
                    <a class="text-blue-600 hover:underline"
                       href="{{ route('admin.pagos.show', $pago) }}">
                        Ver
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500">
                    No hay pagos registrados.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pagos->links() }}
</div>

@endsection
