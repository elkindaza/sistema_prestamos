@extends('layouts.admin')

@section('title', 'Inicio Admin')

@section('content')
<div class="grid grid-cols-3 grid-flow-row gap-6" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
    <!-- EL STYLE DE ARRIBA FUERZA A PONER 3 COLUMNAS PORQUE INICIALMENTE NO DEJABA PONER, SOLO PONIA 1 COLUMNA POR 1 FILA -->



    <a href="{{ route('clientes.index') }}"
        class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1 " style="background:#E0E7FF;color:#4338CA">
        <div class="flex items-center gap-4 mb-3" >
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#E0E7FF;color:#4338CA">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Clientes</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>


    <a href="{{ route('admin.prestamos.index') }}"
        class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1"style="background:#DCFCE7;color:#166534">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#DCFCE7;color:#166534">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-green-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Prestamos</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.cuotas.index') }}"
        class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1" style="background:#CFFAFE;color:#155E75">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#CFFAFE;color:#155E75">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-600" style="color:#0891B2"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3v18h18M9 17V9m4 8V5m4 12v-6" />
                </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Cuotas</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.pagos.index') }}"
        class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1" style="background:#D1FAE5;color:#065F46">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#D1FAE5;color:#065F46">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-600" style="color:#059669"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2 7h20M2 11h20M6 15h2m4 0h6M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z" />
                </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Pagos</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.contribuciones.index') }}"
        class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1" style="background:#FEF3C7;color:#92400E">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#FEF3C7;color:#92400E">
                <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#CA8A04"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Contribuciones</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.caja.index') }}"
       class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1" style="background:#E5E7EB;color:#374151">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#E5E7EB;color:#374151">
                <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#374151"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h16M4 11h16M4 15h16M6 3h12v4H6V3z" />
                    </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Caja</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.asignaciones.index') }}"
         class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm
          hover:shadow-md transition transform hover:-translate-y-1" style="background:#EDE9FE;color:#7C3AED">
        <div class="flex items-center gap-4 mb-3">
            <!-- ICONO -->
            <div class="p-3 rounded-lg"
                style="background:#EDE9FE;color:#7C3AED">
               <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#7C3AED"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h11l-3-3m0 10h8l-3 3m3-3l-3-3" />
                    </svg>
            </div>

            <h3 class="font-semibold text-gray-800">Asignaciones</h3>
        </div>
        <p class="text-sm text-gray-500">
            Crear, editar, inactivar
        </p>
    </a>

    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Utilidades</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>

    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Distribucion</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>

    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Cobranza</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>

    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Documentos</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>
    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Notificaciones</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>
    <a href="{{ route('admin.inicio') }}"
        class="bg-white p-6 rounded shadow hover:shadow-md">
        <h3 class="font-bold">Backups</h3>
        <p class="text-sm text-gray-600">Crear, editar, eliminar</p>
    </a>
</div>
@endsection