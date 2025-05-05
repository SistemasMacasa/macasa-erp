<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColoniaController;

/*
| 30 peticiones por minuto por IP  ▸  throttle:30,1
| Endpoint:  GET /api/cp/{cp}
*/
Route::middleware('throttle:30,1')
     ->get('cp/{cp}', [ColoniaController::class, 'porCp']);
