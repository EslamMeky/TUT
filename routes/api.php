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
    Route::get('detailsUser','UserController@detailsUser');


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

Route::group(['prefix'=>'rating','namespace'=>'App\Http\Controllers\API\Admin','middleware'=>['checkLang']],function (){

    Route::post('add','RatingController@add');
    Route::get('show','RatingController@show');
    Route::get('getUserPlace/{user_id}/{place_id}','RatingController@getUserPlace');
    Route::get('showAllRatingPlace/{place_id}','RatingController@showAllRatingPlace');
    Route::get('getAverageRating/{place_id}','RatingController@getAverageRatingOfPlace');

});

////////////     Searching     //////////
 Route::group(['prefix'=>'searching','namespace'=>'App\Http\Controllers\API\Searching'],function (){
     Route::post('searchAdmin','SearchController@admin');
     Route::post('searchUser','SearchController@user');
     Route::post('searchCity','SearchController@city');
     Route::post('searchCategory','SearchController@category');
     Route::post('searchPlaces','SearchController@places');
     Route::post('searchRating','SearchController@rate');
     Route::get('recommendPlaces','SearchController@recommendPlaces');
 });

 ///    home    /////
Route::group(['prefix'=>'home','namespace'=>'App\Http\Controllers\API\Home'],function (){
    Route::get('index','HomeController@index');
    Route::get('city/{id}','HomeController@city');
    Route::get('place/{id}','HomeController@place');
    Route::get('SeeMoreRestaurant','HomeController@SeeMoreRestaurant');
    Route::get('SeeMoreHotel','HomeController@SeeMoreHotel');
    Route::get('SeeMorePlaceToGo','HomeController@SeeMorePlaceToGo');


    ////     Favorites   ///////////
    Route::group(['prefix'=>'favorites','namespace'=>'Favorites'],function (){
        Route::post('addPlace','FavoritePlaceController@addPlace');
        Route::get('show','FavoritePlaceController@show');
        Route::post('getFavoritePlaces','FavoritePlaceController@getFavoritePlaces');
        Route::post('deleteFavorite','FavoritePlaceController@deleteFavorite');

    });

////////////////////  Trip Plane  ////////////////////
    Route::group(['prefix'=>'trip','namespace'=>'AI'],function (){
        Route::post('generate','TripPlanController@generate');
        Route::post('store', 'TripPlanController@store');
        Route::get('showTrip/{tripId}', 'TripPlanController@showTrip');
        });

   });


Route::get('removeDataInPlaces','App\Http\Controllers\API\Admin\PlacesController@removeDataInPlaces');
