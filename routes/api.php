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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::namespace('App\Http\Controllers')->group(function () {

        //Brand Endpoint
        Route::get('/brand', 'BrandController@index');
        Route::post('/brand', 'BrandController@store');
        Route::put('/brand/{id}', 'BrandController@update');

        //Glue Type Endpoint
        Route::get('/glue', 'GlueTypeController@index');
        Route::post('/glue', 'GlueTypeController@store');
        Route::put('/glue/{id}', 'GlueTypeController@update');

        //Thickness Endpoint
        Route::get('/thickness', 'ThicknessController@index');
        Route::post('/thickness', 'ThicknessController@store');
        Route::put('/thickness/{id}', 'ThicknessController@update');

        //Grade Endpoint
        Route::get('/grade', 'GradeController@index');
        Route::post('/grade', 'GradeController@store');
        Route::put('/grade/{id}', 'GradeController@update');

        //Product Endpoint
        Route::get('/product', 'ProductController@index');
        Route::get('/product/{id}', 'ProductController@show');
        Route::post('/product', 'ProductController@store');
        Route::put('/product/{id}', 'ProductController@update');

        //Panel Endpoint
        Route::get('/panel/{id}', 'StockInController@show');
        Route::put('/panel/{id}', 'StockInController@stockIn');

        //Generate Sticker
        Route::post('/generate', 'GenerateStickerController@generate');
    });