<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDocumento extends Model
{
    protected $table = 'cliente_documentos';

    protected $fillable = [
        'cliente_id',
        'documento_id',
        'tipo',
        'nota',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }
}
