<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table        = 'contactos';
    protected $primaryKey   = 'id_contacto';
    public    $incrementing = true;
    protected $keyType      = 'int';
    public    $timestamps   = false;

    protected $fillable = [
        'id_cliente',
        'nombre','apellido_p','apellido_m',
        'email','telefono1','ext1','telefono2','ext2','puesto',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_p} {$this->apellido_m}");
    }
}
