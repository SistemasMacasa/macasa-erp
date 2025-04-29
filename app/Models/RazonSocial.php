<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    use HasFactory;
    protected $table = 'razones_sociales'; 
    protected $fillable = [
        'nombre',
        'id_cliente',
        'rfc',
        'id_metodo_pago',
        'id_forma_pago',
        'id_regimen_fiscal',
        'id_direccion_facturacion',
        'limite_credito',
        'dias_credito',
        'saldo',
    ];
}
