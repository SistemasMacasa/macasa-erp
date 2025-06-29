<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Colonia;  // <-- importar el modelo

class Direccion extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'direcciones';
    protected $primaryKey = 'id_direccion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_cliente',
        'nombre',
        'tipo',
        'calle',
        'num_ext',
        'num_int',
        'id_colonia',  // <-- nueva FK
        'cp',
        'id_ciudad',
        'id_estado',
        'id_pais',
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

    // Relación a Colonia
    public function colonia()
    {
        return $this->belongsTo(Colonia::class, 'id_colonia', 'id_colonia');
    }

    // Relación a Ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    // Relación a Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }
}
