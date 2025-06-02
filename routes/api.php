<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CalonSantriController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [ApiController::class, 'authenticate'])->middleware('isapi');
Route::post('/registrasi-awal/get-data', [ApiController::class, 'getDataRegistrasiAwal'])->middleware('isapi');
Route::post('/registrasi-awal/add', [ApiController::class, 'addRegistrasiAwal'])->middleware('isapi');

Route::post('/calon-santri/add', [CalonSantriController::class, 'store'])->middleware('api');

Route::get('/create-token', function () {
    return Str::random(32);
})->middleware('api');