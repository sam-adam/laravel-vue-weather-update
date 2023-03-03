<?php

use App\Http\Controllers\Api\UsersV1Controller;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json(['message' => 'all systems are a go']);
});

Route::get('/v1/users', [UsersV1Controller::class, 'index']);
Route::get('/v1/users/{id}', [UsersV1Controller::class, 'show']);
Route::post('/v1/users/{id}/refresh-weather', [UsersV1Controller::class, 'refreshWeather']);
