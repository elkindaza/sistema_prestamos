<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nombre', 'admin')->first();

        if (!$adminRole) {
            throw new \Exception("No existe el rol 'admin'. Ejecuta RolesSeeder primero.");
        }

        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'nombre'     => 'Nico Admin',
                'contrasena' => Hash::make('admin12345'),
                'telefono'   => '3100000000',
                'rol_id'     => $adminRole->id,
                'es_activo'  => true,
            ]
        );
    }
}
