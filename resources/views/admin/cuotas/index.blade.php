
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Cuotas
            </h2>

            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.cuotas.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm text-center">
                    + Nueva cuota
                </a>

                <a href="{{ route('admin.prestamos.index') }}"
                   class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-center">
                    Volver a préstamos
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Filtro --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.cuotas.index') }}" class="flex flex-col md:flex-row gap-3 md:items-end">
                        <div class="w-full md:w-80">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Filtrar por préstamo (ID)
                            </label>
                            <input type="number" name="prestamo_id" min="1"
                                   value="{{ $prestamoId ?? '' }}"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                   placeholder="Ej: 1">
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 rounded bg-gray-900 hover:bg-black text-white text-sm">
                                Filtrar
                            </button>

                            <a href="{{ route('admin.cuotas.index') }}"
                               class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-gray-600 dark:text-gray-300">
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-3 pr-4">Préstamo</th>
                                <th class="py-3 pr-4">Cliente</th>
                                <th class="py-3 pr-4">#</th>
                                <th class="py-3 pr-4">Vence</th>
                                <th class="py-3 pr-4 text-right">Capital</th>
                                <th class="py-3 pr-4 text-right">Interés</th>
                                <th class="py-3 pr-4 text-right">Total</th>
                                <th class="py-3 pr-4">Estado</th>
                                <th class="py-3 pr-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-100">
                        @forelse($cuotas as $cuota)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 pr-4">
                                    <a class="underline hover:no-underline"
                                       href="{{ route('admin.prestamos.show', $cuota->prestamo_id) }}">
                                        #{{ $cuota->prestamo_id }}
                                    </a>
                                </td>

                                <td class="py-3 pr-4">
                                    {{ $cuota->prestamo?->cliente?->nombre_completo ?? '—' }}
                                </td>

                                <td class="py-3 pr-4">
                                    {{ $cuota->numero }}
                                </td>

                                <td class="py-3 pr-4">
                                    {{ \Illuminate\Support\Carbon::parse($cuota->fecha_vencimiento)->format('Y-m-d') }}
                                </td>

                                <td class="py-3 pr-4 text-right">
                                    {{ number_format($cuota->capital_programado, 2, ',', '.') }}
                                </td>

                                <td class="py-3 pr-4 text-right">
                                    {{ number_format($cuota->interes_programado, 2, ',', '.') }}
                                </td>

                                <td class="py-3 pr-4 text-right font-semibold">
                                    {{ number_format($cuota->total_programado, 2, ',', '.') }}
                                </td>

                                <td class="py-3 pr-4">
                                    @php
                                        $badge = match($cuota->estado) {
                                            'pagada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-100',
                                            'vencida' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-100',
                                            'parcial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-100',
                                            default => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
                                        };
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded {{ $badge }}">
                                        {{ $cuota->estado }}
                                    </span>
                                </td>

                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.cuotas.show', $cuota) }}"
                                           class="px-3 py-1.5 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                                            Ver
                                        </a>
                                        <a href="{{ route('admin.cuotas.edit', $cuota) }}"
                                           class="px-3 py-1.5 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                    No hay cuotas registradas.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $cuotas->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
