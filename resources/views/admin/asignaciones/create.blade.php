@extends('layouts.admin')
@section('title', 'Nueva asignación')

@section('content')

<h1 class="text-xl font-semibold mb-4">Nueva asignación de pago</h1>

<form method="POST" action="{{ route('admin.asignaciones.store') }}"
      class="bg-white rounded shadow p-6 space-y-5 max-w-2xl">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Pago *</label>
        <select name="pago_id" required class="w-full rounded border-gray-300">
            <option value="">-- Selecciona --</option>
            @foreach($pagos as $p)
                <option value="{{ $p->id }}">
                    Pago #{{ $p->id }} — ${{ number_format($p->monto,0,',','.') }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Cuota *</label>
        <select name="cuota_id" required class="w-full rounded border-gray-300">
            <option value="">-- Selecciona --</option>
            @foreach($cuotas as $c)
                <option value="{{ $c->id }}">
                    Cuota {{ $c->numero }} — Saldo ${{ number_format($c->saldo_cuota,0,',','.') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Capital</label>
            <input type="number" step="0.01" name="capital_pagado"
                   class="w-full rounded border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Interés</label>
            <input type="number" step="0.01" name="intereses_pagado"
                   class="w-full rounded border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Mora</label>
            <input type="number" step="0.01" name="mora_pagada"
                   class="w-full rounded border-gray-300" required>
        </div>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.asignaciones.index') }}"
           class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>

        <button class="px-4 py-2 bg-indigo-600 text-white rounded">
            Guardar
        </button>
    </div>
</form>

@endsection
