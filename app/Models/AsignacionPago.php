<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionPago extends Model
{
    protected $table = 'asignacion_pagos';

    public $timestamps = false;

    protected $fillable = [
        'pago_id',
        'cuota_id',
        'capital_pagado',
        'intereses_pagado',
        'mora_pagada',
        'asignado_en',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'cuota_id');
    }
}
