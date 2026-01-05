<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r p-4">
            <h1 class="text-lg font-bold mb-6">Sistema Préstamos</h1>

            <nav class="space-y-2 text-sm">

                <a href="{{ route('admin.inicio') }}" onmouseover="this.style.background='#DBEAFE'"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-blue-600" style="color:#2563EB"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1V9.75z" />
                    </svg>
                    <span>Inicio</span>
                </a>

                <a href="{{ route('clientes.index') }}"onmouseover="this.style.background='#E0E7FF'"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-purple-600" style="color: #4F46E5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Clientes</span>
                </a>

                <a href="{{ route('admin.prestamos.index') }}"onmouseover="this.style.background='#DCFCE7'"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-green-600" style="color:#16A34A"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                    <span>Préstamos</span>
                </a>

                <a href="{{ route('admin.cuotas.index') }}"onmouseover="this.style.background='#CFFAFE'"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#0891B2"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3v18h18M9 17V9m4 8V5m4 12v-6" />
                    </svg>
                    <span>Cuotas</span>
                </a>

                <a href="{{ route('admin.pagos.index') }}"onmouseover="this.style.background='#D1FAE5'"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#059669"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2 7h20M2 11h20M6 15h2m4 0h6M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                    <span>Pagos</span>
                </a>

                <a href="{{ route('admin.asignaciones.index') }}"onmouseover="this.style.background='#EDE9FE '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#7C3AED"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h11l-3-3m0 10h8l-3 3m3-3l-3-3" />
                    </svg>
                    <span>Asignaciones</span>
                </a>



                <a href="{{ route('admin.caja.index') }}"onmouseover="this.style.background='#F3F4F6 '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#374151"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h16M4 11h16M4 15h16M6 3h12v4H6V3z" />
                    </svg>
                    <span>Caja</span>
                </a>

                <a href="{{ route('admin.contribuciones.index') }}"onmouseover="this.style.background='#FEF9C3 '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#CA8A04"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Contribuciones</span>
                </a>

                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;" onmouseover="this.style.background='#CCFBF1 '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#0F766E"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>

                    <span>UtilidadesxPeriodo</span>
                </a>
                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;"onmouseover="this.style.background='#FFEDD5  '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#EA580C"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v8m0 0l-4-4m4 4l4-4M4 4h16" />
                    </svg>
                    <span>Distribucion</span>
                </a>
                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;"onmouseover="this.style.background='#FEE2E2  '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#DC2626"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h6l2 5-3 2a11 11 0 005 5l2-3 5 2v6a2 2 0 01-2 2C9.716 24 0 14.284 0 4a2 2 0 012-2z" />
                    </svg>

                    <span>Cobranza</span>
                </a>
                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;"onmouseover="this.style.background='#F1F5F9  '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#475569"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 2h8l5 5v13a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2z" />
                    </svg>
                    <span>Documentos</span>
                </a>
                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;"onmouseover="this.style.background='#FEE2E2  '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#EF4444"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0" />
                    </svg>
                    <span>Notificaciones</span>
                </a>

                <a href="/" style="color: gray; text-decoration: none; cursor: default; pointer-events: none;"onmouseover="this.style.background='#DBEAFE  '"
                    onmouseout="this.style.background='transparent'"
                    class="flex items-center gap-2 px-3 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600" style="color:#1E40AF"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7h16M4 11h16M4 15h16M6 3h12v4H6V3z" />
                    </svg>

                    <span>Backups</span>
                </a>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="w-full text-left px-3 py-2 rounded text-red-600 hover:bg-red-50">
                        Cerrar sesión
                    </button>
                </form>

            </nav>

        </aside>

        <!-- CONTENIDO -->
        <main class="flex-1 w-full p-6">
            <h2 class="text-xl font-semibold mb-6">@yield('title')</h2>

            @yield('content')
        </main>

    </div>

</body>

</html>