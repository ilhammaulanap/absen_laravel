<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AbsenController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/unautorize', function(Request $request) {
    return response()->json([
        'status' => false,
        'code' => 401,
        'message' => 'Unauthorized'], 200);
    })->name('unautorize');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/add_absen', [AbsenController::class, 'add_absen']);
    Route::post('/approve_absen', [AbsenController::class, 'approve_absen']);
    Route::get('/data_absen', [AbsenController::class, 'data_absen']);
});
