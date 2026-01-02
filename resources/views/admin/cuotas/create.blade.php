<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Crear cuota
            </h2>

            <a href="{{ route('admin.cuotas.index') }}"
                class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <form method="POST" action="{{ route('admin.cuotas.store') }}" class="space-y-6">
                @csrf

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Préstamo --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Préstamo <span class="text-red-500">*</span>
                                </label>
                                <select name="prestamo_id"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">-- Selecciona --</option>
                                    @foreach($prestamos as $p)
                                    <option value="{{ $p->id }}" @selected(old('prestamo_id')==$p->id)>
                                        #{{ $p->id }} — {{ $p->cliente?->nombre_completo ?? 'Sin cliente' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('prestamo_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Número --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Número de cuota <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="numero" min="1"
                                    value="{{ old('numero') }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Ej: 1">
                                @error('numero')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Debe ser único por préstamo (no puede repetirse).
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Fecha venc --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Fecha vencimiento <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_vencimiento"
                                    value="{{ old('fecha_vencimiento') }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('fecha_vencimiento')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Capital --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Capital <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" name="capital_programado"
                                    value="{{ old('capital_programado') }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Ej: 700000">
                                @error('capital_programado')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Interés --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Interés <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" name="interes_programado"
                                    value="{{ old('interes_programado') }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Ej: 300000">
                                @error('interes_programado')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Nota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Nota
                            </label>
                            <textarea name="nota" rows="3"
                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Observación...">{{ old('nota') }}</textarea>
                            @error('nota')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- estado--}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                ESTADO
                            </label>
                            <textarea name="estado" rows="3"
                                class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Observación...">{{ old('estado') }}</textarea>
                            @error('estado')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.cuotas.index') }}"
                        class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-sm">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>