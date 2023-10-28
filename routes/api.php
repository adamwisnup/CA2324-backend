<?php

use App\Http\Controllers\AcademyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['cors'])->group(
    function () {
        Route::post('academy', [AcademyController::class, 'create']);

        Route::get('academy-count', [AcademyController::class, 'countdownQuota']);

        Route::get('academy-data-peserta', [AcademyController::class, 'getData']);
        Route::get('academy-data-peserta/{id}', [AcademyController::class, 'getDataById']);
        Route::patch('academy-data-peserta/{id}', [AcademyController::class, 'update']);
        Route::delete('academy-data-peserta/{id}', [AcademyController::class, 'delete']);
    }
);
