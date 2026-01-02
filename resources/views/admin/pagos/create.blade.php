<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar pago
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.pagos.store') }}"
              class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-6">
            @csrf

            {{-- Préstamo --}}
            <div>
                <label class="block text-sm font-medium mb-1">Préstamo</label>
                <select name="prestamo_id" required
                        class="w-full rounded border-gray-300">
                    <option value="">-- Selecciona --</option>
                    @foreach($prestamos as $p)
                        <option value="{{ $p->id }}">
                            #{{ $p->id }} – {{ $p->cliente->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cuota --}}
            <div>
                <label class="block text-sm font-medium mb-1">Cuota</label>
                <input type="number" name="asignacion_id" required
                       class="w-full rounded border-gray-300"
                       placeholder="ID de la cuota pendiente">
                <p class="text-xs text-gray-500 mt-1">
                    (Luego podemos hacer selector automático)
                </p>
            </div>

            {{-- Monto --}}
            <div>
                <label class="block text-sm font-medium mb-1">Monto pagado</label>
                <input type="number" step="0.01" min="1" name="monto" required
                       class="w-full rounded border-gray-300">
            </div>

            {{-- Método --}}
            <div>
                <label class="block text-sm font-medium mb-1">Método</label>
                <select name="metodo" required class="w-full rounded border-gray-300">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="nequi">Nequi</option>
                    <option value="daviplata">Daviplata</option>
                </select>
            </div>

            {{-- Referencia --}}
            <div>
                <label class="block text-sm font-medium mb-1">Referencia</label>
                <input type="text" name="referencia"
                       class="w-full rounded border-gray-300">
            </div>

            {{-- Notas --}}
            <div>
                <label class="block text-sm font-medium mb-1">Notas</label>
                <textarea name="notas" rows="3"
                          class="w-full rounded border-gray-300"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.pagos.index') }}"
                   class="px-4 py-2 rounded bg-gray-200">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded bg-indigo-600 text-white">
                    Registrar pago
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
