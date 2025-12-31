<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'titulo',
        'body',
        'data_json',
        'enviado_en',
        'leido_en',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
