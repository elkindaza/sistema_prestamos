<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Movimiento Caja #{{ $caja->id }}
            </h2>

            <a href="{{ route('admin.caja.index') }}"
               class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm
                      dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Fecha</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ optional($caja->fecha)->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Dirección</div>
                            <div class="inline-flex px-3 py-1 rounded text-sm
                                {{ $caja->direccion === 'IN'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-100'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-100' }}">
                                {{ $caja->direccion }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Monto</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                $ {{ number_format($caja->monto, 2, ',', '.') }}
                            </div>
                        </div>

                        <div class="p-4 rounded bg-gray-50 dark:bg-gray-900/40">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Saldo después</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                $ {{ number_format($caja->saldo_despues, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Concepto</div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $caja->concepto }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Estado</div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $caja->estado }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Referencia</div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $caja->tipo_referencia }} #{{ $caja->id_referencia }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Creado por (user_id)</div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $caja->creado_por }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Documento (doc_id)</div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $caja->doc_id ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Nota</div>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $caja->nota ?? '—' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-4 rounded bg-yellow-100 text-yellow-900 dark:bg-yellow-900/30 dark:text-yellow-100">
                <b>Importante:</b> Caja es solo lectura por auditoría. Si algo está mal, se corrige con
                <b>anulación</b> o <b>asiento de ajuste</b> (más adelante lo haremos formal).
            </div>
        </div>
    </div>
</x-app-layout>
