<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-bold">Clientes</h1>
                        <a class="underline" href="{{ route('clientes.create') }}">Nuevo</a>
                    </div>

                    <form method="GET" class="flex gap-2">
                        <input class="border rounded p-2 w-full" name="q" value="{{ $q }}" placeholder="Buscar por nombre, documento o teléfono">
                        <button class="border rounded px-4">Buscar</button>
                    </form>

                    @if(session('success'))
                    <div class="p-2 border rounded">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Nombre</th>
                                    <th class="text-left">Doc</th>
                                    <th class="text-left">Tel</th>
                                    <th class="text-left">Riesgo</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes as $c)
                                <tr class="border-b">
                                    <td class="py-2">{{ $c->nombre_completo }}</td>
                                    <td>{{ $c->tipo_documento }} {{ $c->numero_documento }}</td>
                                    <td>{{ $c->telefono }}</td>
                                    <td>{{ $c->nivel_riesgo }}</td>
                                    <td>{{ $c->status }}</td>
                                    <td class="text-right">
                                        <a class="underline" href="{{ route('clientes.edit', $c) }}">Editar</a>
                                        <form class="inline" method="POST" action="{{ route('clientes.destroy', $c) }}">
                                            @csrf @method('DELETE')
                                            <button class="underline" onclick="return confirm('¿Inactivar cliente?')">Inactivar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>