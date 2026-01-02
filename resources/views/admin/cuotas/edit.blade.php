<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Cuota #{{ $cuota->numero }} (Préstamo #{{ $cuota->prestamo_id }})
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.cuotas.show', $cuota) }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Ver detalle
                </a>

                <a href="{{ route('admin.cuotas.index', ['prestamo_id' => $cuota->prestamo_id]) }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Errores --}}
            @if ($errors->any())
                <div class="p-4 rounded bg-red-100 text-red-900 dark:bg-red-900/30 dark:text-red-100">
                    <div class="font-semibold mb-2">Corrige estos errores:</div>
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.cuotas.update', $cuota) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Estado --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @foreach(['pendiente','parcial','pagada','vencida'] as $st)
                                        <option value="{{ $st }}" @selected(old('estado', $cuota->estado) === $st)>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Si la pones en <b>pagada</b>, después el sistema bloquea edición.
                                </p>
                            </div>

                            {{-- Vencimiento --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Fecha vencimiento <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_vencimiento"
                                       value="{{ old('fecha_vencimiento', \Illuminate\Support\Carbon::parse($cuota->fecha_vencimiento)->format('Y-m-d')) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('fecha_vencimiento')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Capital --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Capital <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" name="capital_programado"
                                       value="{{ old('capital_programado', $cuota->capital_programado) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('monto_capital')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Interés --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Interés <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" name="interes_programado"
                                       value="{{ old('interes_programado', $cuota->interes_programado) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('monto_interes')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Total (solo lectura) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Total (auto)
                                </label>
                                <input type="text" readonly
                                       value="{{ number_format(($cuota->capital_programado + $cuota->interes_programado), 2, ',', '.') }}"
                                       class="w-full rounded border-gray-200 bg-gray-100 text-gray-700 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-200">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    El total se recalcula al guardar.
                                </p>
                            </div>
                        </div>

                        {{-- Nota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Nota
                            </label>
                            <textarea name="nota" rows="3"
                                      class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('nota', $cuota->nota) }}</textarea>
                            @error('nota')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Préstamo: #{{ $cuota->prestamo_id }} | Cuota: #{{ $cuota->numero }}
                        </div>

                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    {{-- Eliminar (solo si pendiente) --}}
                    @if($cuota->estado === 'pendiente')
                        <form method="POST" action="{{ route('admin.cuotas.destroy', $cuota) }}"
                              onsubmit="return confirm('¿Eliminar esta cuota? Solo recomendable si fue creada por error.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm">
                                Eliminar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.cuotas.show', $cuota) }}"
                       class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-center">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
