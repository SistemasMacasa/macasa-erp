<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';
    protected $primaryKey = 'id_nota';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_usuario',
        'etapa',
        'contenido',
        'fecha_registro',
        'fecha_reprogramacion',
        'es_automatico'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
