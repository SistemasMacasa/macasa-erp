<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    public $table        = 'colonias';
    public $timestamps   = false;
    public $incrementing = false;   // PK compuesta
    protected $guarded   = [];      // permitir mass-assignment
}