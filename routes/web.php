<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DenyController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('check.sessionLogin')->prefix('/')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
});

Route::get('/administrator/deny', [DenyController::class, 'index'])->middleware('check.session');
Route::post('/auth', [AuthController::class, 'Auth']);
Route::get('/logout', [LogoutController::class, 'index']);
