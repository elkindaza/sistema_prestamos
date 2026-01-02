<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribucion extends Model
{
    protected $table = 'contribuciones';

    public $timestamps = false;

    protected $fillable = [
        'asociado_id',
        'monto',
        'aportado_en',
        'metodo',
        'referencia',
        'adjunto_id',
    ];
    protected $casts = [
        'aportado_en' => 'datetime',
        'monto' => 'decimal:2',
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class, 'asociado_id');
    }

    public function adjunto()
    {
        return $this->belongsTo(Documento::class, 'adjunto_id');
    }

    public function movimientoCaja()
    {
        return $this->hasOne(Caja::class, 'id_referencia')->where('tipo_referencia', 'contribucion');
    }
}
