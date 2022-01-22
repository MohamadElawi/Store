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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


####################         user api 

Route::group(['prefix'=>'user','namespace'=>'user'],function(){

    Route::post('login','MainController@login');

    Route::post('logout','MainController@logout')->middleware('checkLogin:user-api');

    Route::post('UserRegister','MainController@UserRegister');
    
});

##################

Route::group(['prefix'=>'product','namespace'=>'product'],function(){

    Route::post('AddProduct','productController@AddProduct')->middleware('checkLogin:user-api');

    Route::get('Get-All-Product','productController@GetAllProduct');

    Route::get('Get-Product-Details/{product_id}','productController@GetProductDetails');

    Route::post('get-prordut','productController@GetAllProductByUserIdByPostMethod');

    Route::get('get/{user_id}','productController@getproduct');

    Route::get('get-user-products','productController@GetUserProducts')->middleware('checkLogin:user-api');

    Route::get('get-user/{product_id}','productController@GetProductUser');

    Route::get('delete-product/{product_id}','productController@DeleteProduct')->middleware('checkLogin:user-api');

    Route::post('edit-product/{product_id}','productController@editProduct')->middleware('checkLogin:user-api');
    
    Route::post('search','productController@search');

    Route::get('CheckExpirDate/{product_id}','productController@CheckDate');

    Route::get('Get-Product-of-category/{category}','productController@GetProductOfCategory');

    Route::get('sort/{sort_By}','productController@sort');
});
########### Comment ##############

Route::group(['prefix'=>'comment','namespace'=>'product'],function(){

    Route::post('{product_id}/Add','commentController@AddComment')->middleware('checkLogin:user-api');

    Route::post('update/{comment_id}','commentController@updateComment');

    Route::delete('delete/{comment_id}','commentController@deleteComment');

    Route::get('{product_id}/GetAllComments','commentController@GetAllComment');

});

Route::group(['prefix'=>'like','namespace'=>'product'],function(){

    Route::post('storeLike/{product_id}','commentController@storeLike')->middleware('checkLogin:user-api');



});