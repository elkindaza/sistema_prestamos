<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asociado extends Model
{
    protected $table = 'asociados';

    protected $fillable = [
        'user_id',
        'estado',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contribuciones()
    {
        return $this->hasMany(Contribucion::class, 'asociado_id');
    }

    public function distribuciones()
    {
        return $this->hasMany(Distribucion::class, 'asociado_id');
    }
}
