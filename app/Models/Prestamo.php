<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';

    protected $fillable = [
        'cliente_id',
        'monto_principal',
        'meses_plazo',
        'tasa_interes',
        'tipo_interes',
        'tipo_cuota',
        'fecha_inicio',
        'fecha_primera_cuota',
        'fecha_vencimiento',
        'frecuencia',
        'estado',
        'aprobado_en',
        'aprobado_por',
        'nota_aprobacion',
        'desembolsado_en',
        'desembolsado_por',
        'documento_desembolso_id',
        'nota',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'prestamo_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'prestamo_id');
    }

    public function acciones()
    {
        return $this->hasMany(Accion::class, 'prestamo_id');
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function desembolsadoPor()
    {
        return $this->belongsTo(User::class, 'desembolsado_por');
    }

    public function documentoDesembolso()
    {
        return $this->belongsTo(Documento::class, 'documento_desembolso_id');
    }
}
