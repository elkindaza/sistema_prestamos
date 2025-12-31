<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoBeneficio extends Model
{
    protected $table = 'periodo_beneficio';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'ingresos_interes',
        'ingresos_moro',
        'gastos',
        'beneficio_neto',
        'calculado_en',
        'calculado_por',
        'cerrado_en',
        'cerrado_por',
        'nota',
    ];

    public function distribuciones()
    {
        return $this->hasMany(Distribucion::class, 'periodo_id');
    }

    public function calculadoPor()
    {
        return $this->belongsTo(User::class, 'calculado_por');
    }

    public function cerradoPor()
    {
        return $this->belongsTo(User::class, 'cerrado_por');
    }
}
