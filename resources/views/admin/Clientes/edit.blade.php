<x-app-layout>
    <div class="p-6 space-y-4">
        <h1 class="text-xl font-bold">Editar cliente</h1>
        @include('admin.clientes._form', ['cliente' => $cliente])
    </div>
</x-app-layout>
