<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const PAGINATE= 10;

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

Route::group(['prefix'=>'admin','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang','guest:admin-api']],function (){

    Route::post('register','AdminController@register');
    Route::post('login','AdminController@login');
    Route::get('show','AdminController@show');
    Route::get('edit/{id}','AdminController@edit');
    Route::post('update/{id}','AdminController@update');
    Route::post('delete/{id}','AdminController@delete');

});
