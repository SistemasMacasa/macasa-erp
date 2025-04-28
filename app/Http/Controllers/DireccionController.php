<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',    // FK a Cliente
        'tipo',          // entrega | factura
        'nombre',        // opcional, puede ser nombre de razón social
        'calle',
        'num_ext',
        'num_int',
        'colonia',
        'id_ciudad',
        'id_estado',
        'id_pais',
        'cp',
    ];
}
