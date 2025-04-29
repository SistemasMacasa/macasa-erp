<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    public $timestamps = false;               // si tu tabla no los usa
    protected $table = 'direcciones';         // si el plural difiere
    protected $fillable = [
        'id_cliente',         //  ←  ¡agregado!
        'tipo',               //  entrega | factura
        'nombre',
        'calle','num_ext','num_int','colonia',
        'id_ciudad','id_estado','id_pais','cp',
    ];

    /* Relaciones */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}

