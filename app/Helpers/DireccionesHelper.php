<?php

use App\Models\Direccion;

if (!function_exists('crearDireccionFactura')) {
    function crearDireccionFactura(array $data)
    {
        return Direccion::create([
            'id_cliente' => $data['id_cliente'] ?? null,
            'tipo'       => 'facturacion',
            'calle'      => trim($data['calle'] ?? ''),
            'num_ext'    => $data['num_ext'] ?? null,
            'num_int'    => $data['num_int'] ?? null,
            'colonia'    => trim($data['colonia'] ?? ''),
            'cp'         => $data['cp'] ?? null,
            'id_ciudad'  => $data['id_ciudad'] ?? null,
            'id_estado'  => $data['id_estado'] ?? null,
            'id_pais'    => $data['id_pais'] ?? 1, // por defecto: México
            'referencias'=> trim($data['referencias'] ?? ''),
            'rfc'        => trim($data['rfc'] ?? ''),
            'razon_social' => trim($data['razon_social'] ?? ''),
            'uso_cfdi'   => $data['uso_cfdi'] ?? null,
            'metodo_pago'=> $data['metodo_pago'] ?? null,
            'forma_pago' => $data['forma_pago'] ?? null,
            'regimen_fiscal' => $data['regimen_fiscal'] ?? null,
            'notas'      => trim($data['notas'] ?? '')
        ]);
    }
}

if (!function_exists('crearDireccionEntrega')) {
    function crearDireccionEntrega(array $data)
    {
        return Direccion::create([
            'id_cliente' => $data['id_cliente'] ?? null,
            'tipo'       => 'entrega',
            'nombre'     => trim($data['nombre'] ?? ''),
            'calle'      => trim($data['calle'] ?? ''),
            'num_ext'    => $data['num_ext'] ?? null,
            'num_int'    => $data['num_int'] ?? null,
            'colonia'    => trim($data['colonia'] ?? ''),
            'cp'         => $data['cp'] ?? null,
            'id_ciudad'  => $data['id_ciudad'] ?? null,
            'id_estado'  => $data['id_estado'] ?? null,
            'id_pais'    => $data['id_pais'] ?? 1,
            'referencias'=> trim($data['referencias'] ?? ''),
            'telefono'   => trim($data['telefono'] ?? ''),
            'notas'      => trim($data['notas'] ?? '')
        ]);
    }
}
