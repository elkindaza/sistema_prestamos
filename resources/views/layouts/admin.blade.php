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

                <a href="{{ route('admin.inicio') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                    🏠 Inicio
                </a>

                <a href="{{ route('clientes.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                    👤 Clientes
                </a>

                <a href="{{ route('admin.prestamos.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                    💰 Préstamos
                </a>

                <a href="{{ route('admin.cuotas.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                    📊 Cuotas
                </a>

                <a href="{{ route('admin.cuotas.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                    📊 Caja
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
        <main class="flex-1 p-6">
            <h2 class="text-xl font-semibold mb-6">@yield('title')</h2>

            @yield('content')
        </main>

    </div>

</body>

</html>