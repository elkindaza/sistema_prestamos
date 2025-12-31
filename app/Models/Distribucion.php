<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribucion extends Model
{
    protected $table = 'distribucion';

    public $timestamps = false;

    protected $fillable = [
        'periodo_id',
        'asociado_id',
        'base_contribucion',
        'porcentaje_participacion',
        'importe_beneficios',
        'estado_pago',
        'pagado_en',
        'documento_pago_id',
    ];

    public function periodo()
    {
        return $this->belongsTo(PeriodoBeneficio::class, 'periodo_id');
    }

    public function asociado()
    {
        return $this->belongsTo(Asociado::class, 'asociado_id');
    }

    public function documentoPago()
    {
        return $this->belongsTo(Documento::class, 'documento_pago_id');
    }

    public function movimientoCaja()
    {
        return $this->hasOne(Caja::class, 'id_referencia')->where('tipo_referencia', 'distribucion');
    }
}
