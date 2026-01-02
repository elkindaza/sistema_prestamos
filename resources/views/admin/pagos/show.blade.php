<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">
                Pago #{{ $pago->id }}
            </h2>

            <a href="{{ route('admin.pagos.index') }}"
               class="text-sm text-gray-600 hover:underline dark:text-gray-300">
                ← Volver
            </a>
        </div>
    </x-slot>

    @php
        // Tomamos la primera asignación (por ahora un pago = una cuota)
        $asig = $pago->asignaciones->first();
        $cuota = $asig?->cuota;
    @endphp

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash --}}
        @if (session('success'))
            <div class="p-4 rounded bg-green-100 text-green-900 dark:bg-green-900/30 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded bg-red-100 text-red-900 dark:bg-red-900/30 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">Información</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-200">
                <div><b>Cliente:</b> {{ $pago->prestamo?->cliente?->nombre_completo ?? '—' }}</div>
                <div><b>Préstamo:</b> #{{ $pago->prestamo_id }}</div>

                <div>
                    <b>Cuota:</b>
                    @if($cuota)
                        #{{ $cuota->numero }} (ID: {{ $cuota->id }})
                    @else
                        —
                    @endif
                </div>

                <div><b>Monto:</b> ${{ number_format($pago->monto, 0, ',', '.') }}</div>
                <div><b>Método:</b> {{ $pago->metodo }}</div>
                <div><b>Estado:</b> {{ $pago->estado }}</div>
                <div><b>Fecha:</b> {{ $pago->pagado_en }}</div>

                <div><b>Referencia:</b> {{ $pago->referencia ?? '—' }}</div>
                <div><b>Recibido por:</b> {{ $pago->recibidoPor?->nombre ?? '—' }}</div>

                <div class="md:col-span-2">
                    <b>Notas:</b>
                    <div class="mt-1 p-3 rounded bg-gray-50 dark:bg-gray-900/40">
                        {{ $pago->notas ?: '—' }}
                    </div>
                </div>
            </div>

            {{-- Detalle de asignación (capital/interés/mora) --}}
            <div class="mt-6 border-t pt-4 text-sm text-gray-800 dark:text-gray-200">
                <h4 class="font-semibold mb-2">Asignación</h4>

                @if($asig)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div><b>Capital pagado:</b> ${{ number_format($asig->capital_pagado, 0, ',', '.') }}</div>
                        <div><b>Intereses pagado:</b> ${{ number_format($asig->intereses_pagado, 0, ',', '.') }}</div>
                        <div><b>Mora pagada:</b> ${{ number_format($asig->mora_pagada, 0, ',', '.') }}</div>
                        <div><b>Asignado en:</b> {{ $asig->asignado_en }}</div>
                    </div>
                @else
                    <div class="p-3 rounded bg-yellow-100 text-yellow-900 dark:bg-yellow-900/30 dark:text-yellow-100">
                        Este pago no tiene asignación registrada en <b>asignacion_pagos</b>.
                        (Revisa el store, porque debería crearla siempre)
                    </div>
                @endif
            </div>

        </div>

        {{-- Acciones --}}
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.pagos.edit', $pago) }}"
               class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-sm">
                Editar
            </a>

            @if($pago->estado === 'registrado')
                <form method="POST" action="{{ route('admin.pagos.anular', $pago) }}"
                      onsubmit="return confirm('¿Seguro de anular este pago?')">
                    @csrf
                    <button class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm">
                        Anular pago
                    </button>
                </form>
            @endif
        </div>

    </div>
</x-app-layout>
