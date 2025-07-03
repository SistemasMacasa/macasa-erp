<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'lider_id',
    ];

    /**
     * Relación con el líder del equipo.
     */
    public function lider()
    {
        return $this->belongsTo(Usuario::class, 'lider_id', 'id_usuario');
    }

    /**
     * Usuarios que pertenecen a este equipo (miembros + líder).
     */
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'equipo_usuario',
            'equipo_id',
            'usuario_id'
        )->withPivot('rol')->withTimestamps();
    }
}
