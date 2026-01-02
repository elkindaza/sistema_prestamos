@extends('layouts.admin')
@section('title', 'Detalle préstamo')

@section('content')
@if(session('success')) <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div> @endif
@if(session('error')) <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">{{ session('error') }}</div> @endif

<div class="bg-white p-6 rounded shadow space-y-3">
  <div class="flex items-center justify-between">
    <div>
      <div class="text-sm text-gray-500">Préstamo #{{ $prestamo->id }}</div>
      <div class="font-semibold">{{ $prestamo->cliente?->nombre_completo }}</div>
      <div class="text-sm">Estado: <span class="px-2 py-1 bg-gray-100 rounded">{{ $prestamo->estado }}</span></div>
    </div>

    <div class="flex gap-2">
      <a class="px-3 py-2 bg-gray-200 rounded" href="{{ route('admin.prestamos.edit', $prestamo) }}">Editar</a>
    </div>
  </div>

  <hr>

  <div class="grid grid-cols-3 gap-4 text-sm">
    <div><b>Monto:</b> ${{ number_format($prestamo->monto_principal, 0, ',', '.') }}</div>
    <div><b>Plazo:</b> {{ $prestamo->meses_plazo }} meses</div>
    <div><b>Tasa:</b> {{ $prestamo->tasa_interes }}</div>
    <div><b>Inicio:</b> {{ $prestamo->fecha_inicio }}</div>
    <div><b>1ra cuota:</b> {{ $prestamo->fecha_primera_cuota }}</div>
    <div><b>Vence:</b> {{ $prestamo->fecha_vencimiento }}</div>
  </div>

  <div class="flex gap-2 pt-4">
    @if($prestamo->estado === 'en_analisis')
      <form method="POST" action="{{ route('admin.prestamos.aprobar', $prestamo) }}">
        @csrf
        <button class="px-3 py-2 bg-green-600 text-white rounded">Aprobar</button>
      </form>
    @endif

    @if($prestamo->estado === 'aprobado')
      <form method="POST" action="{{ route('admin.prestamos.desembolsar', $prestamo) }}">
        @csrf
        <button class="px-3 py-2 bg-blue-600 text-white rounded">Desembolsar (valida caja)</button>
      </form>
    @endif

    <form method="POST" action="{{ route('admin.prestamos.destroy', $prestamo) }}">
      @csrf
      @method('DELETE')
      <button class="px-3 py-2 bg-red-600 text-white rounded">Rechazar</button>
    </form>

    <a class="px-3 py-2 bg-gray-200 rounded" href="{{ route('admin.prestamos.index') }}">Volver</a>
  </div>
</div>

<div class="mt-6 bg-white p-6 rounded shadow">
  <div class="font-semibold mb-2">Cuotas (manual)</div>
  <div class="text-sm text-gray-500">
    Aquí después conectamos el CRUD de cuotas para crearlas manualmente.
  </div>
</div>
@endsection
