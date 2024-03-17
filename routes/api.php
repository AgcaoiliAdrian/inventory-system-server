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
        Route::get('/product', 'ProductController@productList');
        Route::get('/product/{id}', 'ProductController@show');
        Route::post('/product', 'ProductController@store');
        Route::put('/product/{id}', 'ProductController@update');

        //Scanned Barcode Data Endpoint
        Route::get('/scanned/{id}', 'ScanBarcodeController@show');

        //Crate Endpoint --- Stock-In
        Route::get('/crate-in/{id}', 'CrateStockInController@show');
        Route::post('/crate-in/{id}', 'CrateStockInController@tempBatchStockIn'); // This API is for storing temporarily the scanned barcode by batch stack-in
        Route::post('/crate-in', 'CrateStockInController@saveBatchStockIn'); //This API is for saving temporary batch stock-in
        Route::get('/crate-in-temp', 'CrateStockInController@IndexTempBatchIn'); //Get all the temporary stock-in

        //Crate Endpoint --- Stock-Out
        Route::post('/crate-out/{id}', 'CrateStockOutController@tempBatchStockOut'); //This API is for storing temporarily the scanned barcode by batch stock-out
        Route::post('/crate-out', 'CrateStockOutController@saveBatchStockOut'); //This API is for saving temporary batch stock-out
        Route::get('/crate-out-temp', 'CrateStockOutController@IndexTempBatchOut'); //Get all the temporary stock-out 

        //Panel Endpoint --- Stock In
        // Route::post('/panel/{id}', 'PanelStockInController@panelStockIn');
        // Route::get('/panel', 'PanelStockInController@index');
        Route::post('/panel-in/{id}', 'PanelStockInController@tempPanelStockIn');
        Route::post('/panel-in', 'PanelStockInController@savePanelStockIn');

        
        //Stock Out Endpoint
        Route::get('/stock-out', 'StockOutController@index');
        Route::post('/stock-out/{id}', 'StockOutController@stockOut');

        //Generate Sticker
        Route::post('/generate', 'GenerateStickerController@generate');
    });