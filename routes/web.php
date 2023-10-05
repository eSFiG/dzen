<?php

use App\Http\Controllers\CommentController;
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

Route::get('/', [CommentController::class, 'list']);
Route::get('/sort/{name}/{dir}', [CommentController::class, 'list']);
Route::get('/replies/{id}/{page}', [CommentController::class, 'replies']);
