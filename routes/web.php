<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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



Route::get('/', [TaskController::class, 'index']);
Route::get('/get_tasks', [TaskController::class, 'get_tasks']);
Route::post('/store', [TaskController::class, 'store']);
Route::get('edit_task/{id}', [TaskController::class, 'edit']);
Route::put('update_task/{id}', [TaskController::class, 'update']);
Route::delete('delete_task/{id}', [TaskController::class, 'destroy']);


