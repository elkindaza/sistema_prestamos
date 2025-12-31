<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backups';

    public $timestamps = false;

    protected $fillable = [
        'creado_en',
        'creado_por',
        'ubicacion',
        'tamano',
        'estado',
    ];

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
