<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'tipo_cliente',
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'email',
        'direccion',
        'nivel_riesgo',
        'nota',
        'status',
    ];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'cliente_id');
    }

    public function documentos()
    {
        return $this->hasMany(ClienteDocumento::class, 'cliente_id');
    }
}

