<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $primaryKey = 'id_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_razon_social',
        'id_vendedor',
        'fecha_alta',
        'vencimiento',
        'id_direccion_entre',
        'estatus',
        'id_divisa',
        'num_consecutivo',
        'orden_de_venta',
        'score_final',
        'notas_entrega',
        'notas_facturacion',
        'id_termino_pago'
    ];

    // Para que Laravel maneje estas columnas como fechas (Carbon)
    protected $dates = [
        'fecha_alta',
        'vencimiento',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'id_vendedor', 'id_usuario');
    }
}
