<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'username'     => 'prueba4',
                'nombre'       => 'usuario4',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba4',
                'email'        => 'usuario4@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba5',
                'nombre'       => 'usuario5',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba5',
                'email'        => 'usuario5@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba6',
                'nombre'       => 'usuario6',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba6',
                'email'        => 'usuario6@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba7',
                'nombre'       => 'usuario7',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba7',
                'email'        => 'usuario7@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba8',
                'nombre'       => 'usuario8',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba8',
                'email'        => 'usuario8@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba9',
                'nombre'       => 'usuario9',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba9',
                'email'        => 'usuario9@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba10',
                'nombre'       => 'usuario10',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba10',
                'email'        => 'usuario10@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba11',
                'nombre'       => 'usuario11',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba11',
                'email'        => 'usuario11@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba12',
                'nombre'       => 'usuario12',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba12',
                'email'        => 'usuario12@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
            [
                'username'     => 'prueba13',
                'nombre'       => 'usuario13',
                'apellido_p'   => 'de',
                'apellido_m'   => 'prueba13',
                'email'        => 'usuario13@correo.com',
                'password'     => Hash::make('password'),
                'cargo'        => 'Ejecutivo',
                'tipo'         => 'erp',
                'estatus'      => 'activo',
                'id_cliente'   => null,
                'es_admin'     => false,
                'fecha_alta'   => now(),
            ],
        ]);
    }
}
