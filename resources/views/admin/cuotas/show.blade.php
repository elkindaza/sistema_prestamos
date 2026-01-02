<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle Cuota #{{ $cuota->numero }} (Préstamo #{{ $cuota->prestamo_id }})
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.cuotas.edit', $cuota) }}"
                   class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                    Editar
                </a>

                <a href="{{ route('admin.cuotas.index', ['prestamo_id' => $cuota->prestamo_id]) }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">

                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $cuota->prestamo?->cliente?->nombre_completo ?? '—' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Préstamo #{{ $cuota->prestamo_id }}
                            </div>
                        </div>

                        @php
                            $badge = match($cuota->estado) {
                                'pagada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-100',
                                'vencida' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-100',
                                'parcial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-100',
                                default => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
                            };
                        @endphp
                        <span class="text-xs px-3 py-1 rounded {{ $badge }}">
                            Estado: {{ $cuota->estado }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Número</div>
                            <div class="text-lg font-semibold">{{ $cuota->numero }}</div>
                        </div>

                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Vencimiento</div>
                            <div class="text-lg font-semibold">
                                {{ \Illuminate\Support\Carbon::parse($cuota->fecha_vencimiento)->format('Y-m-d') }}
                            </div>
                        </div>

                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Capital</div>
                            <div class="text-lg font-semibold">
                                {{ number_format($cuota->capital_programado, 2, ',', '.') }}
                            </div>
                        </div>

                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Interés</div>
                            <div class="text-lg font-semibold">
                                {{ number_format($cuota->interes_programado, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded bg-indigo-50 text-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-100">
                        <div class="text-xs opacity-80">Total cuota</div>
                        <div class="text-2xl font-bold">
                            {{ number_format($cuota->total_programado, 2, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nota</div>
                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            {{ $cuota->nota ?: '—' }}
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Creada: {{ $cuota->created_at }} | Actualizada: {{ $cuota->updated_at }}
                    </div>
                </div>
            </div>

            {{-- Pagos asignados (si ya existe relación) --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Pagos asignados a esta cuota
                    </h3>

                    @if($cuota->pagos && $cuota->pagos->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left text-gray-600 dark:text-gray-300">
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-2 pr-4">Pago ID</th>
                                        <th class="py-2 pr-4 text-right">Capital</th>
                                        <th class="py-2 pr-4 text-right">Interés</th>
                                        <th class="py-2 pr-4 text-right">Mora</th>
                                        <th class="py-2 pr-4">Asignado en</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cuota->pagos as $ap)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pr-4">{{ $ap->pago_id }}</td>
                                        <td class="py-2 pr-4 text-right">{{ number_format($ap->capital_pagado, 2, ',', '.') }}</td>
                                        <td class="py-2 pr-4 text-right">{{ number_format($ap->intereses_pagado, 2, ',', '.') }}</td>
                                        <td class="py-2 pr-4 text-right">{{ number_format($ap->mora_pagada, 2, ',', '.') }}</td>
                                        <td class="py-2 pr-4">{{ $ap->asignado_en }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Aún no hay pagos asignados a esta cuota.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
