<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',   // FK a Cliente
        'nombre',
        'apellido_p',
        'apellido_m',
        'email',
        'telefono1',
        'ext1',
        'telefono2',
        'ext2',
        'puesto',
        // Si agregas más, los pones aquí
    ];
}
