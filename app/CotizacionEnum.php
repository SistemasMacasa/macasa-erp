<?php
namespace App\Enums;

enum CotizacionEnum:string
{
    /* extensiones seguras */
    public const EXT_PERMITIDAS = ['pdf','doc','docx','xls','xlsx','msg','zip'];

    /* 25 MB en bytes */
    public const MAX_OC = 25 * 1024 * 1024;
}
