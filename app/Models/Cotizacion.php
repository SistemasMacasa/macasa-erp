<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

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
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory;

    /* -----------  tabla & PK ----------- */
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id_cotizacion';
    public $timestamps = false;      // usa tus propios campos de fecha

    /* -----------  mass-assignment  ----------- */
    protected $fillable = [
        'id_cliente',
        'id_razon_social',
        'id_direccion_entrega',
        'id_contacto_entrega',
        'id_vendedor',
        'id_divisa',
        'id_termino_pago', // opcional, si algún día se usa
        'fecha_alta',
        'vencimiento',
        'estatus',
        'num_consecutivo',
        'orden_de_venta',   // (binario)
        'score_final',
    ];

    /* -----------  RELACIONES  ----------- */

    // 1 cotización tiene muchas partidas
    public function partidas()
    {
        return $this->hasMany(
            CotizacionPartida::class,
            'id_cotizacion',
            'id_cotizacion'
        );
    }

    // Ejemplo extra: cliente, razón social, contacto
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function razonSocial()
    {
        return $this->belongsTo(RazonSocial::class, 'id_razon_social');
    }

    public function contactoEntrega()
    {
        return $this->belongsTo(Contacto::class, 'id_contacto');
    }

    /* -----------  ACCESOR útil ----------- */
    public function getSubtotalAttribute()
    {
        return $this->partidas->sum('importe');
    }

    public function getIvaAttribute()
    {
        return $this->subtotal * 0.16;
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->iva;
    }

    public function consecutivo()
    {
        return $this->belongsTo(Consecutivo::class, 'num_consecutivo', 'valor_actual');
    }



>>>>>>> dev
}
