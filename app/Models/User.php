<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nombre',
        'email',
        'contrasena',
        'telefono',
        'rol_id',
        'es_activo',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // ✅ clave: Laravel Auth espera "password", tú tienes "contrasena"
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function asociado()
    {
        return $this->hasOne(Asociado::class, 'user_id');
    }

    public function documentosSubidos()
    {
        return $this->hasMany(Documento::class, 'subido_por');
    }

    public function prestamosAprobados()
    {
        return $this->hasMany(Prestamo::class, 'aprobado_por');
    }

    public function prestamosDesembolsados()
    {
        return $this->hasMany(Prestamo::class, 'desembolsado_por');
    }

    public function pagosRecibidos()
    {
        return $this->hasMany(Pago::class, 'recibido_por');
    }

    public function pagosAnulados()
    {
        return $this->hasMany(Pago::class, 'anulado_por');
    }

    public function accionesCobranzas()
    {
        return $this->hasMany(Accion::class, 'creado_por');
    }

    public function periodosCalculados()
    {
        return $this->hasMany(PeriodoBeneficio::class, 'calculado_por');
    }

    public function periodosCerrados()
    {
        return $this->hasMany(PeriodoBeneficio::class, 'cerrado_por');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function backups()
    {
        return $this->hasMany(Backup::class, 'creado_por');
    }
}
