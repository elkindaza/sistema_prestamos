<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Contribución #{{ $contribucion->id }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.contribuciones.edit', $contribucion) }}"
                   class="px-4 py-2 rounded bg-gray-200">Editar</a>
                <a href="{{ route('admin.contribuciones.index') }}"
                   class="px-4 py-2 rounded bg-gray-200">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
            <div class="p-4 rounded bg-green-100 text-green-900 dark:bg-green-900/30 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded bg-red-100 text-red-900 dark:bg-red-900/30 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Asociado</div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $contribucion->asociado?->user?->nombre ?? '—' }}
                    </div>
                    <div class="text-gray-500">{{ $contribucion->asociado?->user?->email ?? '' }}</div>
                </div>

                <div>
                    <div class="text-gray-500">Monto</div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                        ${{ number_format((float)$contribucion->monto, 2, ',', '.') }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Aportado en</div>
                    <div class="text-gray-900 dark:text-gray-100">
                        {{ optional($contribucion->aportado_en)->format('Y-m-d H:i') }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Método / Referencia</div>
                    <div class="text-gray-900 dark:text-gray-100">
                        {{ $contribucion->metodo }} — {{ $contribucion->referencia ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Documento soporte</div>
                    <div class="text-gray-900 dark:text-gray-100">
                        {{ $contribucion->adjunto?->nombre ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-3">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Movimiento en Caja</h3>

            @if($movCaja)
                <div class="text-sm grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div><span class="text-gray-500">Dirección:</span> <b>{{ $movCaja->direccion }}</b></div>
                    <div><span class="text-gray-500">Estado:</span> <b>{{ $movCaja->estado }}</b></div>
                    <div><span class="text-gray-500">Concepto:</span> {{ $movCaja->concepto }}</div>
                    <div><span class="text-gray-500">Saldo después:</span> ${{ number_format((float)$movCaja->saldo_despues, 2, ',', '.') }}</div>
                </div>
            @else
                <div class="text-sm text-gray-500">No se encontró movimiento de caja asociado.</div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Anular contribución</h3>

            <form method="POST" action="{{ route('admin.contribuciones.anular', $contribucion) }}"
                  class="space-y-3">
                @csrf

                <textarea name="motivo" rows="2"
                          class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100"
                          placeholder="Motivo (opcional)"></textarea>

                <button class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
                        onclick="return confirm('¿Seguro? Esto reversa el dinero en caja.')">
                    Anular y reversar
                </button>
            </form>

            <p class="text-xs text-gray-500 mt-2">
                Esto NO borra: marca el movimiento original como anulado y crea un OUT de reverso.
            </p>
        </div>

    </div>
</x-app-layout>
