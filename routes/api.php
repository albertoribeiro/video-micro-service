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
//Route::resource(name:'Categories', controller:'Api\CategoryController');
Route::resource('categories', 'Api\CategoryController',['except'=> ['create','edit']]);
Route::resource('genres', 'Api\GenreController',['except'=> ['create','edit']]);
Route::resource('cast_members', 'Api\CastMemberController',['except'=> ['create','edit']]);
