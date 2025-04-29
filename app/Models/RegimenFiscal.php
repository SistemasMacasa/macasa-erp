<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegimenFiscal extends Model
{
    use HasFactory;
    protected $table = 'regimen_fiscales'; // Nombre de la tabla en la base de datos
    protected $fillable = [
        'clave',
        'nombre',
        'tipo_persona',
    ];
}
