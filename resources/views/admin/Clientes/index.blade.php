@extends('layouts.admin')
@section('title', 'Clientes')

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

<div class="flex items-center justify-between mb-4">
    <form class="flex gap-2" method="GET">
        <input
            name="q"
            value="{{ $q }}"
            class="border rounded px-3 py-2"
            placeholder="Buscar por nombre, documento o teléfono">
        <button class="px-3 py-2 bg-gray-800 text-white rounded">
            Buscar
        </button>
    </form>

    <a href="{{ route('clientes.create') }}"
        class="px-3 py-2 bg-blue-600 text-white rounded">
        + Nuevo cliente
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#E0E7FF">
            <tr>
                <th class="p-3 text-left ">Nombre</th>
                <th class="p-3 text-left">Documento</th>
                <th class="p-3 text-left">Teléfono</th>
                <th class="p-3 text-left">Riesgo</th>
                <th class="p-3 text-left">Estado</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $c)
            <tr class="border-t hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-center">{{ $c->nombre_completo }}</td>
                <td class="px-4 py-3 text-center">
                    {{ $c->tipo_documento }} {{ $c->numero_documento }}
                </td>
                <td class="px-4 py-3 text-center">{{ $c->telefono }}</td>
                <td class="px-4 py-3 text-center">{{ $c->nivel_riesgo }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full ">
                        
                        @if($c->status === 'activo')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full "
                            style="background:#DCFCE7;color:#166534">
                            Activo
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full "
                            style="background:#FEE2E2;color:#991B1B">
                            Inactivo
                        </span>
                        @endif
                    </span>
                </td>
                <td class="p-2 ">
                    <div class="flex justify-center items-center gap-2">

                        <!-- EDITAR -->
                        <a href="{{ route('clientes.edit', $c) }}"
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

                        <!-- INACTIVAR -->
                        <form method="POST"
                            action="{{ route('clientes.destroy', $c) }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                onclick="return confirm('¿Inactivar cliente?')"
                                class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium transition"
                                style="background:#FEE2E2;color:#991B1B"
                                onmouseover="this.style.background='#FCA5A5'"
                                onmouseout="this.style.background='#FEE2E2'">

                                <!-- ICONO ELIMINAR -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                                </svg>

                                Inactivar
                            </button>
                        </form>

                    </div>
                </td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $clientes->links() }}
</div>

@endsection