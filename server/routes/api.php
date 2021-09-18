<?php

use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
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

Route::post('signup', [UserController::class, 'create']);
Route::post('login', [UserController::class, 'login']);

Route::get('advertises', [AdvertiseController::class, 'all']);

Route::group(['middleware' => ['api-auth']], function() {
    Route::get('auth-test', function() {
        return 'Authentication working';
    });
    Route::post('advertise/create', [AdvertiseController::class, 'create']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
