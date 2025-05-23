<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    public $timestamps = false;               // si tu tabla no los usa
    protected $table = 'direcciones';         // si el plural difiere
    protected $primaryKey = 'id_direccion';
    // Como es INT auto-incremental:
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_cliente',        
        'id_direccion_entrega',   
        'id_direccion_facturacion', 
        'tipo',               //  entrega | factura
        'nombre',
        'calle',
        'num_ext',
        'num_int',
        'colonia',
        'id_ciudad',
        'id_estado',
        'id_pais',
        'cp',
    ];

    /* Relaciones */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function contactosEntrega()
    {
        return $this->hasMany(Contacto::class, 'id_direccion_entrega', 'id_direccion');
    }

    public function razonSocial()
    {
        return $this->hasMany(RazonSocial::class, 'id_direccion_facturacion', 'id_direccion');
    }
    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_direccion_entrega', 'id_direccion');
    }
}

