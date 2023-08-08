<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::resource('user', CommonController::class);
Route::get('user-list', [CommonController::class, 'index']);
Route::post('store-user', [CommonController::class, 'store']);
Route::post('edit-user', [CommonController::class, 'edit']);
Route::post('delete-user', [CommonController::class, 'destroy']);