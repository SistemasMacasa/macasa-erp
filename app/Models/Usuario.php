<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


//Hay dos tipos de usuarios: Internos y Externos
// Internos: ERP, CRM, etc.
// Externos: Ecommerce, etc.
// Un usuario sin id_cliente es un usuario interno
// Un usuario con id_cliente es un usuario externo
// Todos los clientes tienen un vendedor asignado (id_vendedor)
// Prohibido: tener un usuario interno con id_cliente
// Prohibido: tener un usuario externo como vendedor de otro usuario externo

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario'; // Laravel espera 'id' por defecto

    public $timestamps = false; // Solo si no usas created_at / updated_at

    protected $fillable = [
        'username', 'email', 'password', 'tipo', 'estatus', 'es_admin'
    ];

    // Opcional: si quieres que Laravel reconozca el "name"
    public function getNameAttribute()
    {
        return $this->username;
    }

    // 1) Un usuario interno (tipo=ERP && id_cliente==NULL) puede manejar varios clientes
    public function clientesManejados()
    {
        return $this->hasMany(Cliente::class, 'id_vendedor', 'id_usuario');
        // en la tabla "clientes", la fk se llama "id_vendedor"
        // se refiere a "usuarios.id_usuario"
    }

    // 2) Si el usuario es externo (tipo=ECOMMERCE),
    // su campo "id_cliente" apunta a un registro en la tabla "clientes"
    public function clienteAsociado()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
        // "id_cliente" en "usuarios" apunta a "id_cliente" en "clientes"
    }
}
