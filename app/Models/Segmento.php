<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segmento extends Model
{
    use HasFactory;

    // Si tu clave primaria no es 'id', define la propiedad:
    protected $primaryKey = 'id_segmento';

    // Si no usas timestamps en la tabla, añade:
    // public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        // 'id_sucursal' si corresponde o quieres manejarlo aquí
    ];

    // Relación inversa: Un segmento tiene muchos clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_segmento', 'id_segmento');
    }
}
