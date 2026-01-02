<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Nueva contribución
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

        @if($errors->any())
            <div class="p-4 rounded bg-red-100 text-red-900 dark:bg-red-900/30 dark:text-red-100">
                <div class="font-semibold mb-2">Corrige estos errores:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.contribuciones.store') }}"
              class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Asociado (contribuyente) *</label>
                <select name="asociado_id" required
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">-- Selecciona --</option>
                    @foreach($asociados as $a)
                        <option value="{{ $a->id }}" @selected(old('asociado_id') == $a->id)>
                            #{{ $a->id }} — {{ $a->user?->nombre }} ({{ $a->user?->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Monto *</label>
                    <input type="number" step="0.01" min="1" name="monto" required
                           value="{{ old('monto') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Fecha/hora aportado *</label>
                    <input type="datetime-local" name="aportado_en" required
                           value="{{ old('aportado_en', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Método *</label>
                    <select name="metodo" required
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @foreach(['transferencia','efectivo','nequi','daviplata'] as $m)
                            <option value="{{ $m }}" @selected(old('metodo') === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Referencia</label>
                    <input type="text" name="referencia" value="{{ old('referencia') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100"
                           placeholder="TRX-APO-001">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Documento soporte (opcional)</label>
                <select name="adjunto_id"
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">-- Ninguno --</option>
                    @foreach($documentos as $d)
                        <option value="{{ $d->id }}" @selected(old('adjunto_id') == $d->id)>
                            #{{ $d->id }} — {{ $d->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Por ahora es ID. Luego lo hacemos selector completo / upload.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nota para caja (opcional)</label>
                <textarea name="nota_caja" rows="2"
                          class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100"
                          placeholder="Ej: aporte mensual de enero">{{ old('nota_caja') }}</textarea>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.contribuciones.index') }}" class="px-4 py-2 rounded bg-gray-200">
                    Cancelar
                </a>
                <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                    Guardar contribución
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
