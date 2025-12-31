<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'acciones';

    public $timestamps = false;

    protected $fillable = [
        'prestamo_id',
        'accion_en',
        'canal',
        'resultado',
        'siguiente_accion_en',
        'notas',
        'creado_por',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
