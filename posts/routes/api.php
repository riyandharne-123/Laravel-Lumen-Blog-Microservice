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

Route::get('/getAllPosts', 'App\Http\Controllers\PostController@getPosts');
Route::get('/getUsersPosts', 'App\Http\Controllers\PostController@getUsersPosts');
Route::get('/getPost', 'App\Http\Controllers\PostController@getPost');
Route::post('/createPost', 'App\Http\Controllers\PostController@createPost');
