@extends('layouts.admin')
@section('title', 'Editar asignación')

@section('content')

<h1 class="text-xl font-semibold mb-4">
    Editar asignación #{{ $asignacion->id }}
</h1>

<div class="p-4 mb-4 bg-yellow-100 text-yellow-800 rounded">
    ⚠️ Solo se ajustan valores contables.  
    No se cambia pago ni cuota.
</div>

<form method="POST"
      action="{{ route('admin.asignaciones.update', $asignacion) }}"
      class="bg-white rounded shadow p-6 space-y-5 max-w-xl">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Capital</label>
            <input type="number" step="0.01" name="capital_pagado"
                   value="{{ $asignacion->capital_pagado }}"
                   class="w-full rounded border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Interés</label>
            <input type="number" step="0.01" name="intereses_pagado"
                   value="{{ $asignacion->intereses_pagado }}"
                   class="w-full rounded border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Mora</label>
            <input type="number" step="0.01" name="mora_pagada"
                   value="{{ $asignacion->mora_pagada }}"
                   class="w-full rounded border-gray-300" required>
        </div>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.asignaciones.show', $asignacion) }}"
           class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>

        <button class="px-4 py-2 bg-indigo-600 text-white rounded">
            Guardar cambios
        </button>
    </div>
</form>

@endsection
