<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Pago #{{ $pago->id }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.pagos.show', $pago) }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm
                          dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Ver detalle
                </a>

                <a href="{{ route('admin.pagos.index') }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm
                          dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Volver a pagos
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
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

            <form method="POST" action="{{ route('admin.pagos.update', $pago) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- CARD: Resumen no editable (para no romper caja/asignación) --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Resumen (solo lectura)
                            </h3>

                            <span class="text-xs px-2 py-1 rounded
                                @if($pago->estado === 'registrado') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-100
                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-100
                                @endif
                            ">
                                Estado: {{ $pago->estado }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                                <div class="text-gray-500 dark:text-gray-400">Cliente</div>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $pago->prestamo->cliente->nombre_completo ?? '—' }}
                                </div>

                                <div class="text-gray-500 dark:text-gray-400 mt-3">Préstamo</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    #{{ $pago->prestamo_id }}
                                </div>
                            </div>

                            <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                                <div class="text-gray-500 dark:text-gray-400">Cuota</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    #{{ optional($pago->asignaciones)->numero ?? '—' }}
                                </div>

                                <div class="text-gray-500 dark:text-gray-400 mt-3">Monto</div>
                                <div class="text-gray-900 dark:text-gray-100 font-semibold">
                                    ${{ number_format($pago->monto, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Para evitar inconsistencias: el <b>monto</b>, <b>préstamo</b> y <b>cuota</b> no se editan desde aquí.
                            Si necesitas corregir un pago, lo correcto es <b>anular</b> y registrar uno nuevo (o implementar “reverso y re-aplicación”).
                        </p>
                    </div>
                </div>

                {{-- CARD: Campos editables --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Datos editables
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Método --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Método <span class="text-red-500">*</span>
                                </label>
                                <select name="metodo"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @php
                                        $metodos = ['efectivo','transferencia','nequi','daviplata','otro'];
                                        $metodoActual = old('metodo', $pago->metodo);
                                    @endphp
                                    @foreach($metodos as $m)
                                        <option value="{{ $m }}" @selected($metodoActual === $m)>{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('metodo')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Referencia --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Referencia
                                </label>
                                <input type="text" name="referencia"
                                       value="{{ old('referencia', $pago->referencia) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="Ej: TRX-123 / Comprobante / Nequi...">
                                @error('referencia')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- doc_id --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Documento soporte (doc_id)
                                </label>
                                <input type="number" name="doc_id" min="1"
                                       value="{{ old('doc_id', $pago->doc_id) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="Ej: 3">
                                @error('doc_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Luego lo haremos selector / upload.
                                </p>
                            </div>

                            {{-- Fecha pago (si quieres permitir ajuste administrativo) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Fecha y hora del pago
                                </label>
                                <input type="datetime-local" name="pagado_en"
                                       value="{{ old('pagado_en', \Illuminate\Support\Carbon::parse($pago->pagado_en)->format('Y-m-d\TH:i')) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('pagado_en')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Útil para corregir fecha del registro si fue digitada mal.
                                </p>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Notas
                            </label>
                            <textarea name="notas" rows="4"
                                      class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                      placeholder="Observaciones...">{{ old('notas', $pago->notas) }}</textarea>
                            @error('notas')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($pago->estado === 'anulado')
                            <div class="p-4 rounded bg-yellow-100 text-yellow-900 dark:bg-yellow-900/30 dark:text-yellow-100 text-sm">
                                Este pago está <b>ANULADO</b>. Se permiten cambios solo informativos (método, referencia, notas),
                                pero no tiene efecto contable.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.pagos.show', $pago) }}"
                       class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm
                              dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-center">
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
