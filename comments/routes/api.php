<?php

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

Route::get('/getAllComments', 'App\Http\Controllers\CommentController@getAllComments');
Route::get('/getAllCommentsForPost', 'App\Http\Controllers\CommentController@getAllCommentsForPost');
Route::post('/createComment', 'App\Http\Controllers\CommentController@createComment');
