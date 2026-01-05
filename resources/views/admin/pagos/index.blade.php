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
            placeholder="Buscar por cliente o préstamo">
        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Buscar
        </button>
    </form>

    <a href="{{ route('admin.pagos.create') }}"
        class="px-3 py-2 bg-blue-600 text-white rounded">
        + Nuevo pago
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#E0E7FF">
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
            <tr class="border-t hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-center">#{{ $pago->id }}</td>

                <td class="px-4 py-3 text-center">
                    {{ $pago->prestamo?->cliente?->nombre_completo ?? '—' }}
                </td>
                <td class="px-4 py-3 text-center">
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


                <td class="px-4 py-3 text-center">
                    ${{ number_format($pago->monto, 0, ',', '.') }}
                </td>

                <td class="px-4 py-3 text-center">
                    @php
                    $badge = $pago->estado === 'registrado'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                        {{ $pago->estado }}
                    </span>
                </td>


                <td class="px-4 py-3 text-center">
                    {{ \Carbon\Carbon::parse($pago->pagado_en)->format('Y-m-d H:i') }}
                </td>


                <td class="p-2">
                    <div class="flex justify-center items-center gap-2">
                        <a href="{{ route('admin.pagos.show', $pago) }}"
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