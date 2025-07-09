<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consecutivo extends Model
{
    protected $table = 'consecutivos';
    protected $fillable = ['tipo', 'prefijo', 'valor_actual'];

    /**
     * Devuelve el siguiente folio completo y actualiza valor_actual.
     * Ej.: MC200123
     */
    public function siguiente(): string
    {
        $this->increment('valor_actual');

        return $this->prefijo .
            str_pad($this->valor_actual, 5, '0', STR_PAD_LEFT);
    }
}
