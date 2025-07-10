<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetasVentas extends Model
{
    protected $table = 'metas_ventas'; // Tu tabla correcta

    protected $primaryKey = 'id_meta_venta';

    public $timestamps = false; // Si tu tabla no tiene created_at y updated_at

    protected $fillable = [
        'id_usuario',
        'mes',
        'anio',
        'mes_aplicacion',
        'cuota_facturacion',
        'cuota_marginal_facturacion',
        'dias_meta',
        'cuota_cotizaciones',
        'cotizaciones_diarias',
        'cuota_marginal_cotizaciones',
        'cuota_llamadas',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
