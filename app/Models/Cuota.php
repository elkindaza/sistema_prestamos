<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'prestamo_id',
        'numero',
        'fecha_vencimiento',
        'capital_programado',
        'interes_programado',
        'total_programado',
        'interes_pagado',
        'mora_pagada',
        'total_pagado',
        'saldo_cuota',
        'estado',
        'pagado_en',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'pagado_en' => 'datetime',
        'capital_programado' => 'decimal:2',
        'interes_programado' => 'decimal:2',
        'total_programado' => 'decimal:2',
        'interes_pagado' => 'decimal:2',
        'mora_pagada' => 'decimal:2',
        'total_pagado' => 'decimal:2',
        'saldo_cuota' => 'decimal:2',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionPago::class, 'cuota_id');
    }
}
