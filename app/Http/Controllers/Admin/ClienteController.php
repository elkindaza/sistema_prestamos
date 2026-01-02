<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $clientes = Cliente::query()
            ->when($q, fn($query) =>
                $query->where('nombre_completo', 'like', "%{$q}%")
                      ->orWhere('numero_documento', 'like', "%{$q}%")
                      ->orWhere('telefono', 'like', "%{$q}%")
            )
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes','q'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        Cliente::create($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    // Recomendación: NO borrar. Inactivar.
    public function destroy(Cliente $cliente)
    {
        $cliente->update(['status' => 'inactivo']);
        return redirect()->route('clientes.index')->with('success', 'Cliente inactivado.');
    }
}
