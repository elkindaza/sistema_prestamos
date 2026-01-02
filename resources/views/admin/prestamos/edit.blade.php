<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Préstamo #{{ $prestamo->id }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.prestamos.show', $prestamo) }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Ver detalle
                </a>

                <a href="{{ route('admin.prestamos.index') }}"
                   class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                    Volver a préstamos
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

            <form method="POST" action="{{ route('admin.prestamos.update', $prestamo) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- CARD: Datos generales --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Datos generales
                            </h3>

                            <span class="text-xs px-2 py-1 rounded
                                @if($prestamo->estado === 'activo') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-100
                                @elseif($prestamo->estado === 'en_mora') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-100
                                @elseif($prestamo->estado === 'finalizado') bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-100
                                @endif
                            ">
                                Estado: {{ $prestamo->estado }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Cliente --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Cliente <span class="text-red-500">*</span>
                                </label>
                                <select name="cliente_id"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">-- Selecciona --</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}"
                                            @selected(old('cliente_id', $prestamo->cliente_id) == $c->id)>
                                            {{ $c->nombre_completo }} ({{ $c->tipo_documento }} {{ $c->numero_documento }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @php
                                        $estados = ['en_analisis','aprobado','rechazado','desembolsado','activo','en_mora','finalizado'];
                                    @endphp
                                    @foreach($estados as $st)
                                        <option value="{{ $st }}" @selected(old('estado', $prestamo->estado) === $st)>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Recomendación: no cambies a <b>activo</b> si no se ha desembolsado.
                                </p>
                            </div>
                        </div>

                        {{-- Nota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Nota / Observación
                            </label>
                            <textarea name="nota" rows="3"
                                      class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                      placeholder="Ej: se le consigna por Nequi, condiciones, observaciones...">{{ old('nota', $prestamo->nota) }}</textarea>
                            @error('nota')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- CARD: Condiciones financieras --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Condiciones financieras
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Monto --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Monto principal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="1" name="monto_principal"
                                       value="{{ old('monto_principal', $prestamo->monto_principal) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="10000000.00">
                                @error('monto_principal')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Plazo --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Meses de plazo <span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="1" max="360" name="meses_plazo"
                                       value="{{ old('meses_plazo', $prestamo->meses_plazo) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="12">
                                @error('meses_plazo')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tasa --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Tasa de interés (mensual) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.0001" min="0" max="1" name="tasa_interes"
                                       value="{{ old('tasa_interes', $prestamo->tasa_interes) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="0.0300">
                                @error('tasa_interes')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Ej: 0.0300 = 3% mensual
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Tipo interés --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Tipo interés <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_interes"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @foreach(['mensual'] as $v)
                                        <option value="{{ $v }}" @selected(old('tipo_interes', $prestamo->tipo_interes) === $v)>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_interes')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tipo cuota --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Tipo cuota <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_cuota"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @foreach(['fija','capital_fijo'] as $v)
                                        <option value="{{ $v }}" @selected(old('tipo_cuota', $prestamo->tipo_cuota) === $v)>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_cuota')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Frecuencia --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Frecuencia <span class="text-red-500">*</span>
                                </label>
                                <select name="frecuencia"
                                        class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                    @foreach(['mensual','quincenal'] as $v)
                                        <option value="{{ $v }}" @selected(old('frecuencia', $prestamo->frecuencia) === $v)>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('frecuencia')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CARD: Fechas --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Fechas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Fecha inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_inicio"
                                       value="{{ old('fecha_inicio', $prestamo->fecha_inicio) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('fecha_inicio')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Primera cuota <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_primera_cuota"
                                       value="{{ old('fecha_primera_cuota', $prestamo->fecha_primera_cuota) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('fecha_primera_cuota')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Vencimiento <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_vencimiento"
                                       value="{{ old('fecha_vencimiento', $prestamo->fecha_vencimiento) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @error('fecha_vencimiento')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Tip: Si cambias fechas, asegúrate de que tus cuotas manuales (si ya existen) queden coherentes.
                        </p>
                    </div>
                </div>

                {{-- CARD: Aprobación y desembolso (metadatos) --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Aprobación y desembolso (metadatos)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nota aprobación --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Nota de aprobación
                                </label>
                                <textarea name="nota_aprobacion" rows="3"
                                          class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                          placeholder="Ej: Aprobado por mayoría, condiciones, etc.">{{ old('nota_aprobacion', $prestamo->nota_aprobacion) }}</textarea>
                                @error('nota_aprobacion')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Documento desembolso id (por ahora manual) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Documento desembolso (ID)
                                </label>
                                <input type="number" name="documento_desembolso_id"
                                       value="{{ old('documento_desembolso_id', $prestamo->documento_desembolso_id) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                       placeholder="Ej: 2">
                                @error('documento_desembolso_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Luego lo haremos selector de documentos / upload.
                                </p>
                            </div>
                        </div>

                        {{-- Solo lectura (info sistema) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                                <div class="text-gray-500 dark:text-gray-400">Aprobado en</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $prestamo->aprobado_en ?? '—' }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400 mt-2">Aprobado por (user_id)</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $prestamo->aprobado_por ?? '—' }}
                                </div>
                            </div>

                            <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                                <div class="text-gray-500 dark:text-gray-400">Desembolsado en</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $prestamo->desembolsado_en ?? '—' }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400 mt-2">Desembolsado por (user_id)</div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $prestamo->desembolsado_por ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Nota: estos campos normalmente se setean por acciones (Aprobar / Desembolsar), no “a mano”.
                        </p>
                    </div>
                </div>

                {{-- FOOTER acciones --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.prestamos.show', $prestamo) }}"
                       class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-center">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-blue text-sm">
                        Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
