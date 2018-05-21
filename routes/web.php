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



Route::get('/', 'IndexController@index');
Route::get('result', 'ResultController@index');
Route::get('login/{provider}', 'Auth\SocialAccountController@redirectToProvider')->middleware('guest');
//Route::get('login/amazon', 'AmazonController@index');
//Route::get('login/amazon/callback', 'AmazonController@callback');
Route::get('login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');
Route::get('logout', 'IndexController@logout');
Route::delete('delete/{id}', 'IndexController@delete');
Auth::routes();

Route::get('policy', 'SitepolicyContoroller@index');
