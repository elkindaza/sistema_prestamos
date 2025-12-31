<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    public $timestamps = false; // tu tabla solo tiene created_at, pero no updated_at

    protected $fillable = [
        'ruta_archivo',
        'nombre',
        'tamano',
        'subido_por',
        'subido_en',
        'mime_type',
    ];

    public function subidoPor()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function clientes()
    {
        return $this->hasMany(ClienteDocumento::class, 'documento_id');
    }
}
