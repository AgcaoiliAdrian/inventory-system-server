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

Route::namespace('App\Http\Controllers')->group(function () {

    //User Login and Registration
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');

    // Protected routes that require authentication
    // Route::middleware(['auth:sanctum'])->group(function () {

        //Brand Endpoint
        Route::get('/brand', 'BrandController@index');
        Route::post('/brand', 'BrandController@store');
        Route::put('/brand/{id}', 'BrandController@update');
        Route::get('/brand/{id}', 'BrandController@show');
        Route::delete('/brand/{id}', 'BrandController@delete');

        //Glue Type Endpoint
        Route::get('/glue', 'GlueTypeController@index');
        Route::post('/glue', 'GlueTypeController@store');
        Route::put('/glue/{id}', 'GlueTypeController@update');
        Route::get('/glue/{id}', 'GlueTypeController@show');
        Route::delete('/glue/{id}', 'GlueTypeController@delete');

        //Thickness Endpoint
        Route::get('/thickness', 'ThicknessController@index');
        Route::post('/thickness', 'ThicknessController@store');
        Route::put('/thickness/{id}', 'ThicknessController@update');
        Route::get('/thickness/{id}', 'ThicknessController@show');
        Route::delete('/thickness/{id}', 'ThicknessController@delete');

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
        Route::get('/crate-in', 'CrateStockInController@index');
        Route::get('/crate-in/{barcode}', 'CrateStockInController@show');
        Route::post('/crate-in/{barcode}', 'CrateStockInController@tempBatchStockIn'); // This API is for storing temporarily the scanned barcode by batch stack-in
        Route::post('/crate-in', 'CrateStockInController@saveBatchStockIn'); //This API is for saving temporary batch stock-in
        Route::get('/crate-in-temp', 'CrateStockInController@IndexTempBatchIn'); //Get all the temporary stock-in
        Route::delete('/crate-in-temp/{id}', 'CrateStockInController@delete'); //Get all the temporary stock-in

        //Crate Endpoint --- Stock-Out
        Route::get('/crate-out', 'CrateStockOutController@index'); //Get All the crates with status out
        Route::post('/crate-out/{barcode}', 'CrateStockOutController@tempBatchStockOut'); //This API is for storing temporarily the scanned barcode by batch stock-out
        Route::post('/crate-out', 'CrateStockOutController@saveBatchStockOut'); //This API is for saving temporary batch stock-out
        Route::get('/crate-out-temp', 'CrateStockOutController@IndexTempBatchOut'); //Get all the temporary stock-out 
        Route::delete('/crate-out-temp/{id}', 'CrateStockOutController@delete'); //Get all the temporary stock-out 
        Route::post('/insert-out/{barcode}', 'CrateStockOutController@insertOne'); //This API is for inserting one panel to the crate.

        //Panel Endpoint --- Stock In
        // Route::post('/panel/{id}', 'PanelStockInController@panelStockIn');
        Route::get('/panel-in', 'PanelStockInController@index');//Get All the panels with status in
        Route::post('/panel-in/{barcode}', 'PanelStockInController@tempPanelStockIn'); //
        Route::post('/panel-in', 'PanelStockInController@savePanelStockIn');
        Route::get('/panel-in-temp', 'PanelStockInController@IndexTempPanelIn'); //Get all the temporary stock-out 
        Route::delete('/panel-in-temp/{id}', 'PanelStockInController@delete'); //Get all the temporary stock-out 

        //Panel Endpoint --- Stock Out
        Route::get('/panel-out', 'PanelStockOutController@index'); //Get All the panels with status out
        Route::post('/panel-out/{barcode}', 'PanelStockOutController@tempPanelStockOut');
        Route::post('/panel-out', 'PanelStockOutController@savePanelStockOut');
        Route::delete('/panel-out/{id}', 'PanelStockOutController@delete');
        Route::get('/panel-out-temp', 'PanelStockOutController@IndexTempPanelOut'); //Get all the temporary stock-out 
        
        //Stock Out Endpoint
        Route::get('/stock-out', 'StockOutController@index');
        Route::post('/stock-out/{id}', 'StockOutController@stockOut');

        //Batch Number End Points
        Route::get('/batch', 'CrateStockInController@batchNumber');
    
        //Dashboard End Points
        Route::get('/stocks', 'DashboardController@stocksData'); // Get stocks data
        Route::get('/top-selling', 'DashboardController@topSelling'); // Top Selling data
        Route::get('/revenue', 'DashboardController@revenue'); //revenue data
        Route::get('/sticker-usage', 'DashboardController@stickerUsage'); //sticker usage data

        //Generate Sticker
        Route::post('/generate', 'GenerateStickerController@generate');
        
    // });

    // Catch-all route for unauthorized requests
    Route::fallback(function () {
        return response()->json(['message' => 'Unauthorized.'], 401);
    });
});
