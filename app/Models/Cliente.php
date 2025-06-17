<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Todos los clientes tienen un vendedor asignado (id_vendedor)
class Cliente extends Model
{
    protected $table = 'clientes'; // Solo si el nombre no sigue la convención 'clientes'
    protected $primaryKey = 'id_cliente'; // Si no es 'id'
    public $timestamps = false; // Si tu tabla no tiene created_at / updated_at
    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'ciclo_venta',
        'estatus',
        'tipo',
        'id_vendedor',
        'sector',
        'segmento'
    ];

    // Devuelve el usuario interno (vendedor) asignado
    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'id_vendedor', 'id_usuario');
        // "id_vendedor" en "clientes" apunta a "id_usuario" en "usuarios"
    }
    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'id_cliente');
    }
    /** Todos los contactos del cliente */
    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_cliente', 'id_cliente');
    }
    /** El contacto principal (el “predeterminado”) */
    public function contacto_predet()
    {
        return $this->hasOne(Contacto::class, 'id_cliente', 'id_cliente')
                    ->where('predeterminado', 1);
    }
    /** El primer contacto (el “principal”) */
    public function primerContacto()
    {
        return $this->hasOne(Contacto::class, 'id_cliente', 'id_cliente')
            ->oldestOfMany('id_contacto');
    }
    public function razonesSociales()
    {
        return $this->hasMany(RazonSocial::class, 'id_cliente', 'id_cliente');
    }
    public function razon_social_predet()
    {
        return $this->hasOne(RazonSocial::class, 'id_cliente', 'id_cliente')
                    ->where('predeterminado', 1);
    }
    public function direccionesEntrega()
    {
        return $this->hasMany(Direccion::class, 'id_cliente', 'id_cliente')
                    ->whereIn('id_direccion', function ($query) {
                        $query->select('id_direccion_entrega')
                            ->from('contactos')
                            ->whereNotNull('id_direccion_entrega');
                    });
    }
    public function contacto_entrega_predet()
    {
        return $this->hasOne(Contacto::class, 'id_cliente', 'id_cliente')
                    ->where('predeterminado', 1)
                    ->whereNotNull('id_direccion_entrega');
    }
    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_cliente', 'id_cliente')->orderBy('fecha_registro', 'desc');
    }

}
