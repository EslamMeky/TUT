<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const PAGINATE= 10;

Route::group(['middleware'=>['auth.guard:api','checkLang'],'namespace'=>'App\Http\Controllers\API\User'],function (){
    Route::get('logoutUser','AuthController@logout');

});

Route::group(['middleware'=>['api','checkLang'],'namespace'=>'App\Http\Controllers\API\User'],function (){

    /////////////  Register  /////////////////
    Route::get('getUser','AuthController@index');
    Route::post('registerUser','AuthController@register');
    Route::post('loginUser','AuthController@login');



    //////////////////////////User /////////////////

    Route::post('updateUser/{id}','UserController@update');
    Route::get('editUser/{id}','UserController@edit');




});
