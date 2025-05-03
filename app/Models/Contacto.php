<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Existen dos tipos de contactos: 1) Contacto de Cuenta Eje 2) Contacto de Dirección de Entrega.

/*
 Un contacto de cuenta eje tiene id_direccion_entrega = null
 Si el contacto tiene id_direccion_entrega entonces es un contacto de dirección de entrega
*/
class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';
    protected $primaryKey = 'id_contacto';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_direccion_entrega',
        'nombre',
        'apellido_p',
        'apellido_m',
        'email',
        'telefono1',
        'ext1',
        'telefono2',
        'ext2',
        'telefono3',
        'ext3',
        'telefono4',
        'ext4',
        'telefono5',
        'ext5',
        'puesto',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_p} {$this->apellido_m}");
    }
    public function direccionEntrega()
    {
        return $this->belongsTo(Direccion::class, 'id_direccion_entrega', 'id_direccion');
    }
}
