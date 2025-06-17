<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    use HasFactory;
    protected $table = 'razones_sociales'; 
    protected $fillable = [
        'nombre',
        'id_cliente',
        'RFC',
        'id_metodo_pago',
        'id_forma_pago',
        'id_regimen_fiscal',
        'id_direccion_facturacion',
        'id_uso_cfdi',
        'saldo',
        'limite_credito',
        'dias_credito',
        'created_at',
        'updated_at',
        'predeterminado'
    ];

    public function direccion_facturacion()
    {
        return $this->belongsTo(Direccion::class, 'id_direccion_facturacion', 'id_direccion');
    }

    public function uso_cfdi()
    {
        return $this->belongsTo(UsoCfdi::class, 'id_uso_cfdi', 'id_uso_cfdi');
    }

    public function metodo_pago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function forma_pago()
    {
        return $this->belongsTo(FormaPago::class, 'id_forma_pago', 'id_forma_pago');
    }

    public function regimen_fiscal()
    {
        return $this->belongsTo(RegimenFiscal::class, 'id_regimen_fiscal', 'id_regimen_fiscal');
    }


}
