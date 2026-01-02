@php
  $isEdit = isset($cliente);
@endphp

<form method="POST" action="{{ $isEdit ? route('clientes.update', $cliente) : route('clientes.store') }}" class="space-y-3">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div>
        <label>Tipo</label>
        <select name="tipo_cliente" class="border rounded p-2 w-full" required>
            @foreach(['persona','empresa'] as $t)
                <option value="{{ $t }}" @selected(old('tipo_cliente', $cliente->tipo_cliente ?? 'persona') === $t)>{{ $t }}</option>
            @endforeach
        </select>
        @error('tipo_cliente')<div class="text-sm">{{ $message }}</div>@enderror
    </div>

    <div>
        <label>Nombre completo</label>
        <input name="nombre_completo" class="border rounded p-2 w-full" value="{{ old('nombre_completo', $cliente->nombre_completo ?? '') }}" required>
        @error('nombre_completo')<div class="text-sm">{{ $message }}</div>@enderror
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <label>Tipo doc</label>
            <input name="tipo_documento" class="border rounded p-2 w-full" value="{{ old('tipo_documento', $cliente->tipo_documento ?? 'CC') }}" required>
            @error('tipo_documento')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Número doc</label>
            <input name="numero_documento" class="border rounded p-2 w-full" value="{{ old('numero_documento', $cliente->numero_documento ?? '') }}" required>
            @error('numero_documento')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <label>Teléfono</label>
            <input name="telefono" class="border rounded p-2 w-full" value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
            @error('telefono')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Email</label>
            <input name="email" type="email" class="border rounded p-2 w-full" value="{{ old('email', $cliente->email ?? '') }}">
            @error('email')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
    </div>

    <div>
        <label>Dirección</label>
        <input name="direccion" class="border rounded p-2 w-full" value="{{ old('direccion', $cliente->direccion ?? '') }}">
        @error('direccion')<div class="text-sm">{{ $message }}</div>@enderror
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <label>Nivel riesgo</label>
            <select name="nivel_riesgo" class="border rounded p-2 w-full" required>
                @foreach(['bajo','medio','alto'] as $r)
                    <option value="{{ $r }}" @selected(old('nivel_riesgo', $cliente->nivel_riesgo ?? 'medio') === $r)>{{ $r }}</option>
                @endforeach
            </select>
            @error('nivel_riesgo')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Status</label>
            <select name="status" class="border rounded p-2 w-full" required>
                @foreach(['activo','inactivo'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $cliente->status ?? 'activo') === $s)>{{ $s }}</option>
                @endforeach
            </select>
            @error('status')<div class="text-sm">{{ $message }}</div>@enderror
        </div>
    </div>

    <div>
        <label>Nota</label>
        <textarea name="nota" class="border rounded p-2 w-full" rows="3">{{ old('nota', $cliente->nota ?? '') }}</textarea>
        @error('nota')<div class="text-sm">{{ $message }}</div>@enderror
    </div>

    <button class="border rounded px-4 py-2">
        {{ $isEdit ? 'Actualizar' : 'Crear' }}
    </button>
</form>
