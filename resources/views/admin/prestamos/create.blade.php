@extends('layouts.admin')
@section('title', 'Crear préstamo')

@section('content')
@if($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc ml-5">
      @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('admin.prestamos.store') }}" class="bg-white p-6 rounded shadow space-y-4">
  @csrf

  <div>
    <label class="block text-sm mb-1">Cliente</label>
    <select name="cliente_id" class="border rounded px-3 py-2 w-full" required>
      <option value="">-- Selecciona --</option>
      @foreach($clientes as $c)
        <option value="{{ $c->id }}" @selected(old('cliente_id')==$c->id)>{{ $c->nombre_completo }} ({{ $c->tipo_documento }} {{ $c->numero_documento }})</option>
      @endforeach
    </select>
  </div>

  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="block text-sm mb-1">Monto principal</label>
      <input name="monto_principal" value="{{ old('monto_principal') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Plazo (meses)</label>
      <input name="meses_plazo" type="number" value="{{ old('meses_plazo', 12) }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Tasa interés (ej: 0.0300)</label>
      <input name="tasa_interes" value="{{ old('tasa_interes', '0.0300') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
  </div>

  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="block text-sm mb-1">Tipo interés</label>
      <select name="tipo_interes" class="border rounded px-3 py-2 w-full" required>
        <option value="mensual" selected>mensual</option>
      </select>
    </div>

    <div>
      <label class="block text-sm mb-1">Tipo cuota</label>
      <select name="tipo_cuota" class="border rounded px-3 py-2 w-full" required>
        <option value="fija" @selected(old('tipo_cuota')=='fija')>fija</option>
        <option value="capital_fijo" @selected(old('tipo_cuota')=='capital_fijo')>capital_fijo</option>
      </select>
    </div>

    <div>
      <label class="block text-sm mb-1">Frecuencia</label>
      <select name="frecuencia" class="border rounded px-3 py-2 w-full" required>
        <option value="mensual" @selected(old('frecuencia','mensual')=='mensual')>mensual</option>
        <option value="quincenal" @selected(old('frecuencia')=='quincenal')>quincenal</option>
      </select>
    </div>
  </div>

  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="block text-sm mb-1">Fecha inicio</label>
      <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Primera cuota</label>
      <input type="date" name="fecha_primera_cuota" value="{{ old('fecha_primera_cuota') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Vencimiento</label>
      <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
  </div>

  <div>
    <label class="block text-sm mb-1">Nota</label>
    <textarea name="nota" class="border rounded px-3 py-2 w-full" rows="3">{{ old('nota') }}</textarea>
  </div>

  <div class="flex gap-2">
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
    <a href="{{ route('admin.prestamos.index') }}" class="px-4 py-2 bg-gray-200 rounded">Volver</a>
  </div>
</form>
@endsection
