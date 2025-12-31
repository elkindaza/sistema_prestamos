<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Asociado;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AsociadosSeeder extends Seeder
{
    public function run(): void
    {
        $rolAsociado = Role::where('nombre', 'asociado')->first();

        $user = User::firstOrCreate(
            ['email' => 'asociado1@demo.com'],
            [
                'nombre'     => 'Asociado Uno',
                'contrasena' => Hash::make('asociado123'),
                'telefono'   => '3110000000',
                'rol_id'     => $rolAsociado->id,
                'es_activo'  => true,
            ]
        );

        Asociado::firstOrCreate(
            ['user_id' => $user->id],
            [
                'estado' => 'activo',
                'notes'  => 'Asociado inicial del sistema',
            ]
        );
        $rolAsociado = Role::where('nombre', 'asociado')->first();

        if (!$rolAsociado) {
            throw new \Exception("No existe el rol 'asociado'. Revisa RolesSeeder.");
        }
    }
}
