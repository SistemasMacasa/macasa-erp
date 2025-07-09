<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CotizacionPartida extends Model
{
    use HasFactory;

    protected $table      = 'cotizaciones_partidas';
    protected $primaryKey = 'id_cotizacion_partida';
    public    $timestamps = false;

    protected $fillable = [
        'id_cotizacion',
        'sku',
        'descripcion',
        'cantidad',
        'precio',
        'costo',
        'importe',
        'score',
        'id_proveedor',  // opcional, si algún día se usa
    ];

    /* -----------  RELACIÓN inversa ----------- */
    public function cotizacion()
    {
        return $this->belongsTo(
            Cotizacion::class,
            'id_cotizacion',
            'id_cotizacion'
        );
    }

    /* -----------  MUTATORS automáticos ----------- */

    // Para asegurarnos de que importe y score siempre queden consistentes
    protected static function booted()
    {
        static::creating(function ($p) {
            $p->importe = $p->precio * $p->cantidad;
            $p->score   = $p->importe - ($p->costo * $p->cantidad);
        });

        static::updating(function ($p) {
            $p->importe = $p->precio * $p->cantidad;
            $p->score   = $p->importe - ($p->costo * $p->cantidad);
        });
    }
}
