<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users;
use App\Http\Controllers\Roles;


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



Route::post('/login', [Users::class, 'signin']);
Route::post('/register', [Users::class, 'register']);

Route::get('/getUser', [Users::class, 'getUser']);
Route::get('/Users', [Users::class, 'records']);
Route::post('/Users', [Users::class, 'Users']);
Route::put('/Users/{id}', [Users::class, 'set']);
Route::delete('/Users/{id}', [Users::class, 'remove']);
Route::get('/getUser/{id}', [Users::class, 'getUserById']);


Route::get('/Roles', [Roles::class, 'records']);
Route::post('/Role', [Roles::class, 'insert']);
Route::put('/Roles/{id}', [Roles::class, 'set']);
Route::delete('/Role/{id}', [Roles::class, 'remove']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    
   // Route::get('/getUser', [Users::class, 'getUser']);
   // Route::get('/Users', [Users::class, 'records']);
    //Route::post('/Users', [Users::class, 'Users']);
    //Route::put('/Users/{id}', [Users::class, 'set']);
    //Route::delete('/Users/{id}', [Users::class, 'remove']);
});
