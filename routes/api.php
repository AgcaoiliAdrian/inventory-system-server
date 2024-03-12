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
        Route::get('/brand/{id}', 'BrandController@show');

        //Glue Type Endpoint
        Route::get('/glue', 'GlueTypeController@index');
        Route::post('/glue', 'GlueTypeController@store');
        Route::put('/glue/{id}', 'GlueTypeController@update');
        Route::get('/glue/{id}', 'GlueTypeController@show');

        //Thickness Endpoint
        Route::get('/thickness', 'ThicknessController@index');
        Route::post('/thickness', 'ThicknessController@store');
        Route::put('/thickness/{id}', 'ThicknessController@update');
        Route::get('/thickness/{id}', 'ThicknessController@show');

        //Grade Endpoint
        Route::get('/grade', 'GradeController@index');
        Route::post('/grade', 'GradeController@store');
        Route::put('/grade/{id}', 'GradeController@update');
        Route::get('/grade/{id}', 'GradeController@show');

        //Product Endpoint
        Route::get('/product', 'ProductController@index');
        Route::get('/product/{id}', 'ProductController@show');
        Route::post('/product', 'ProductController@store');
        Route::put('/product/{id}', 'ProductController@update');

        //Scanned Barcode Data Endpoint
        Route::get('/scanned/{id}', 'ScanBarcodeController@show');

        //Panel Endpoint
        Route::get('/panel/{id}', 'StockInController@show');
        Route::post('/panel/{id}', 'StockInController@batchStockIn');
        Route::post('/stock-in', 'StockInController@stockIn');
        
        //Stock Out Endpoint
        Route::get('/stock-out', 'StockOutController@index');
        Route::post('/stock-out/{id}', 'StockOutController@stockOut');

        //Generate Sticker
        Route::post('/generate', 'GenerateStickerController@generate');
    });