<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table      = 'estados';
    protected $primaryKey = 'id_estado';   
    public    $timestamps = false;

    // c_estado se conserva para poder enlazar con colonias / ciudades
    protected $guarded = [];
}
