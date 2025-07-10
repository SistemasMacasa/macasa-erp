<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ciudad;
use App\Models\Estado;

class Colonia extends Model
{
    protected $table      = 'colonias';
    protected $primaryKey = 'id_colonia';
    public    $timestamps = false;

    /* Estado – sin cambios */
    public function estado()
    {
        return $this->belongsTo(
            Estado::class,
            'c_estado',   // FK en colonias
            'c_estado'    // “PK lógica” en estados
        );
    }


    public function ciudad()
    {
        // Sirve para lazy-load (->ciudad) en una sola colonia
        return $this->hasOne(Ciudad::class, 'c_mnpio', 'c_mnpio')
                    ->where('c_estado', $this->c_estado);
    }

}

