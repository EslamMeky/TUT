<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const PAGINATE= 4;

Route::group(['middleware'=>['auth.guard:api','checkLang'],'namespace'=>'App\Http\Controllers\API\User'],function (){
    Route::get('logoutUser','AuthController@logout');

});

Route::group(['middleware'=>['api','checkLang'],'namespace'=>'App\Http\Controllers\API\User'],function (){

    /////////////  Register  /////////////////
    Route::get('showUser','AuthController@index');
    Route::post('registerUser','AuthController@register');
    Route::post('loginUser','AuthController@login');

    /////////////////////////User /////////////////
    Route::post('updateUser/{id}','UserController@update');
    Route::get('editUser/{id}','UserController@edit');
    Route::post('deleteUser/{id}','UserController@delete');

});

////////// admin ////////
Route::group(['prefix'=>'admin','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang','guest:admin-api']],function (){

    Route::post('register','AdminController@register');
    Route::post('login','AdminController@login');
    Route::get('show','AdminController@show');
    Route::get('edit/{id}','AdminController@edit');
    Route::post('update/{id}','AdminController@update');
    Route::post('delete/{id}','AdminController@delete');

});

//////// Cites  ////////////////

Route::group(['prefix'=>'admin/city','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang','guest:admin-api']],function (){

    Route::post('add','CityController@add');
    Route::get('show','CityController@show');
    Route::get('edit/{id}','CityController@edit');
    Route::post('update/{id}','CityController@update');
    Route::post('delete/{id}','CityController@delete');

});

/////////////// category ///////////

Route::group(['prefix' => 'admin/category', 'namespace' => 'App\Http\Controllers\API\Admin', 'middleware' => ['checkLang', 'guest:admin-api']], function () {

    Route::post('add', 'CategoryController@add');
    Route::get('show', 'CategoryController@show');
    Route::get('edit/{id}', 'CategoryController@edit');
    Route::post('update/{id}', 'CategoryController@update');
    Route::post('delete/{id}', 'CategoryController@delete');

});

///////////   places  ////////////

Route::group(['prefix'=>'admin/place','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang','guest:admin-api']],function (){

    Route::post('add','PlacesController@add');
    Route::get('show','PlacesController@show');
    Route::get('edit/{id}','PlacesController@edit');
    Route::post('update/{id}','PlacesController@update');
    Route::post('delete/{id}','PlacesController@delete');

});


///////////   Rating  ////////////

Route::group(['prefix'=>'admin/rating','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang','guest:admin-api']],function (){

    Route::post('add','RatingController@add');
    Route::get('show','RatingController@show');
    Route::get('getUserPlace/{user_id}/{place_id}','RatingController@getUserPlace');
    Route::get('showAllRatingPlace/{place_id}','RatingController@showAllRatingPlace');
    Route::get('getAverageRating/{place_id}','RatingController@getAverageRatingOfPlace');

});
