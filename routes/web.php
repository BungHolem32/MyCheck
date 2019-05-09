<?php

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
    return redirect('/movies/2/recommendations');
});

Route::get('/movies/{movie_id}/recommendations', 'RecommendationsController@index');
Route::get('/movies/{movie_id}/recommendations/{depth?}', 'RecommendationsController@index')
    ->where(['depth' => '[0-3]+']);
