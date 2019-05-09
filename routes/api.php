<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function () {
    return view('welcome');
});

//There are two choices to get to this api

//ABSOLUTE URL
Route::get('/movie-recommendations/', 'RecommendationsController@index');

//HIERARCHICAL URL
Route::get('/movies/{movie_id}/recommendations/', 'RecommendationsController@index');
Route::get('/movies/{movie_id}/recommendations/?depth={depth?}', 'RecommendationsController@index');
