<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';

    protected $fillable = [
        'nombre',
        'id_segmento',
    ];

    // Una sucursal pertenece a un segmento
    public function segmento()
    {
        return $this->belongsTo(Segmento::class, 'id_segmento', 'id_segmento');
    }

    // Una sucursal tiene muchos equipos
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_sucursal', 'id_sucursal');
    }
}
