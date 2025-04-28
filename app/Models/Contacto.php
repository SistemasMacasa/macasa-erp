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
        'apellido_paterno',
        'apellido_materno',
        'email',
        'telefono',
        'ext',
        'telefono2',
        'ext2',
        'puesto',
        // Si agregas más, los pones aquí
    ];
}
