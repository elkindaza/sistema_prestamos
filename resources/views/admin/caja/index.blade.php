@extends('layouts.admin')
@section('title', 'Caja')

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
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Caja (Ledger)</h2>
        <p class="text-sm text-gray-500">
            Solo lectura. Se alimenta por contribuciones, desembolsos y pagos.
        </p>
    </div>

    <div class="text-right">
        <div class="text-xs text-gray-500">Saldo actual</div>
        <div class="text-xl font-bold text-gray-900">
            $ {{ number_format($saldoActual, 2, ',', '.') }}
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="mb-4 bg-white rounded shadow p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">

        <input
            name="q"
            value="{{ $q }}"
            class="border rounded px-3 py-2 md:col-span-2"
            placeholder="concepto / id / tipo / id_ref"
        >

        <select name="tipo" class="border rounded px-3 py-2">
            <option value="">Tipo</option>
            @foreach(['contribucion','prestamo','pago','distribucion','gasto'] as $t)
                <option value="{{ $t }}" @selected($tipo === $t)>{{ $t }}</option>
            @endforeach
        </select>

        <select name="dir" class="border rounded px-3 py-2">
            <option value="">Dirección</option>
            <option value="IN" @selected($dir === 'IN')>IN</option>
            <option value="OUT" @selected($dir === 'OUT')>OUT</option>
        </select>

        <select name="estado" class="border rounded px-3 py-2">
            <option value="">Estado</option>
            <option value="normal" @selected($estado === 'normal')>normal</option>
            <option value="anulado" @selected($estado === 'anulado')>anulado</option>
        </select>

        <input type="date" name="from" value="{{ $from }}"
               class="border rounded px-3 py-2">

        <input type="date" name="to" value="{{ $to }}"
               class="border rounded px-3 py-2">

        <div class="md:col-span-6 flex justify-end gap-2">
            <a href="{{ route('admin.caja.index') }}"
               class="px-3 py-2 bg-gray-200 text-gray-800 rounded">
                Limpiar
            </a>
            <button class="px-3 py-2 bg-gray-800 text-white rounded">
                Filtrar
            </button>
        </div>
    </form>
</div>

{{-- Tabla --}}
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-left">Dir</th>
                <th class="p-3 text-right">Monto</th>
                <th class="p-3 text-left">Concepto</th>
                <th class="p-3 text-left">Referencia</th>
                <th class="p-3 text-right">Saldo después</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($movimientos as $m)
            <tr class="border-t">
                <td class="p-3">{{ $m->id }}</td>

                <td class="p-3">
                    {{ optional($m->fecha)->format('Y-m-d H:i') }}
                </td>

                <td class="p-3">
                    <span class="px-2 py-1 rounded
                        {{ $m->direccion === 'IN'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800' }}">
                        {{ $m->direccion }}
                    </span>
                </td>

                <td class="p-3 text-right">
                    $ {{ number_format($m->monto, 2, ',', '.') }}
                </td>

                <td class="p-3">{{ $m->concepto }}</td>

                <td class="p-3">
                    <div class="text-xs text-gray-500">{{ $m->tipo_referencia }}</div>
                    <div class="font-semibold">#{{ $m->id_referencia }}</div>
                </td>

                <td class="p-3 text-right font-semibold">
                    $ {{ number_format($m->saldo_despues, 2, ',', '.') }}
                </td>

                <td class="p-3">
                    <span class="px-2 py-1 rounded
                        {{ $m->estado === 'normal'
                            ? 'bg-gray-100 text-gray-800'
                            : 'bg-yellow-100 text-yellow-900' }}">
                        {{ $m->estado }}
                    </span>
                </td>

                <td class="p-3">
                    <a class="text-blue-600"
                       href="{{ route('admin.caja.show', $m) }}">
                        Ver
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="p-6 text-center text-gray-500">
                    No hay movimientos en caja con esos filtros.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $movimientos->links() }}
</div>

@endsection
