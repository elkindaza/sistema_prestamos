<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'tipo_cliente' => ['required', Rule::in(['persona','empresa'])],
            'nombre_completo' => ['required','string','max:150'],
            'tipo_documento' => ['required','string','max:20'],
            'numero_documento' => [
                'required','string','max:30',
                Rule::unique('clientes')
                    ->where(fn($q) => $q->where('tipo_documento', $this->tipo_documento))
                    ->ignore($clienteId),
            ],
            'telefono' => ['required','string','max:30'],
            'email' => ['nullable','email','max:150'],
            'direccion' => ['nullable','string','max:200'],
            'nivel_riesgo' => ['required', Rule::in(['bajo','medio','alto'])],
            'nota' => ['nullable','string'],
            'status' => ['required', Rule::in(['activo','inactivo'])],
        ];
    }
}

