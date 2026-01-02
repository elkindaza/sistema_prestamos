<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'prestamo_id',
        'pagado_en',
        'metodo',
        'referencia',
        'doc_id',
        'recibido_por',
        'notas',
        'monto',
        'estado',
        'anulado_en',
        'anulado_por',
        'motivo_anulacion',
        'tipo',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }

    public function recibidoPor()
    {
        return $this->belongsTo(User::class, 'recibido_por');
    }

    public function anuladoPor()
    {
        return $this->belongsTo(User::class, 'anulado_por');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'doc_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionPago::class, 'pago_id');
    }
    public function cuotas()
    {
        return $this->belongsToMany(Cuota::class, 'asignacion_pagos', 'pago_id', 'cuota_id')
            ->withPivot(['capital_pagado','intereses_pagado','mora_pagada','asignado_en'])
            ->withoutTimestamps();
    }

    public function movimientoCaja()
    {
        // Relación “manual” por tipo_referencia/id_referencia (no FK)
        return $this->hasOne(Caja::class, 'id_referencia')->where('tipo_referencia', 'pago');
    }
}
