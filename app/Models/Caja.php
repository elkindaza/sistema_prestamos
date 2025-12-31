<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'caja';

    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'monto',
        'direccion',
        'concepto',
        'tipo_referencia',
        'id_referencia',
        'creado_por',
        'doc_id',
        'saldo_despues',
        'estado',
        'nota',
    ];

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'doc_id');
    }
}
