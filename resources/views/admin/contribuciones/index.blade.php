@extends('layouts.admin')
@section('title', 'Contribuciones')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="flex items-start justify-between mb-4 gap-4">
    <form class="flex flex-wrap gap-2" method="GET">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            class="border rounded px-3 py-2"
            placeholder="Buscar: id, referencia, nombre, email"
        >

        <input
            type="date"
            name="desde"
            value="{{ $desde }}"
            class="border rounded px-3 py-2"
        >

        <input
            type="date"
            name="hasta"
            value="{{ $hasta }}"
            class="border rounded px-3 py-2"
        >

        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Filtrar
        </button>

        <a href="{{ route('admin.contribuciones.index') }}"
           class="px-3 py-2 bg-gray-200 text-gray-800 rounded">
            Limpiar
        </a>
    </form>

    <a href="{{ route('admin.contribuciones.create') }}"
       class="px-3 py-2 bg-blue-600 text-white rounded whitespace-nowrap">
        + Nueva contribución
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#E0E7FF">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Asociado</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-right">Monto</th>
                <th class="p-3 text-left">Método</th>
                <th class="p-3 text-left">Referencia</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contribuciones as $c)
            <tr class="border-t hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-center">#{{ $c->id }}</td>

                <td class="px-4 py-3 text-center">
                    <div class="font-medium">
                        {{ $c->asociado?->user?->nombre ?? '—' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $c->asociado?->user?->email ?? '' }}
                    </div>
                </td>

                <td class="px-4 py-3 text-center">
                    {{ optional($c->aportado_en)->format('Y-m-d H:i') }}
                </td>

                <td class="px-4 py-3 text-right">
                    ${{ number_format((float)$c->monto, 2, ',', '.') }}
                </td>

                <td class="px-4 py-3 text-center">{{ $c->metodo }}</td>

                <td class="px-4 py-3 text-center">{{ $c->referencia ?? '—' }}</td>

                <td class="p-2">
                   
                    <div class="flex justify-center items-center gap-2">
                        <!-- EDITAR -->
                        <a href="{{ route('admin.contribuciones.edit', $c) }}"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition"
                            style="background:#E5E7EB;color:#374151"
                            onmouseover="this.style.background='#D1D5DB'"
                            onmouseout="this.style.background='#E5E7EB'">

                            <!-- ICONO EDITAR -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5h2m-1 0v14m-7-7h14" />
                            </svg>

                            Editar
                        </a>
                        <a href="{{ route('admin.contribuciones.show', $c) }}"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition"
                            style="background:#DCFCE7;color:#166534"
                            onmouseover="this.style.background='#D1D5DB'"
                            onmouseout="this.style.background='#E5E7EB'">

                            <!-- ICONO ver -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5
             c4.478 0 8.268 2.943 9.542 7
             -1.274 4.057-5.064 7-9.542 7
             -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver
                        </a>
                    </div>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-6 text-center text-gray-500">
                    No hay contribuciones.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $contribuciones->links() }}
</div>

@endsection
