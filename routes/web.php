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

Route::get('/ffffffffffffffff', function () {
    return view('welcome');
});

Route::get('/search', function () {
    return view('search_result');
});

Route::get('/product/{id}', ['uses' => "priceDashboard@renderView"]);

Route::get('/ping', function () {
    return "pong";
});

Route::get('/hepsiburada/add_product', "CrawlController@add_new_hepsiburada");
Route::get('/trendyol/add_product', "CrawlController@add_new_trendyol");
Route::get('/price/{id}', ['uses' => "PriceController@get_with_id"])->name('price.get_with_id');;
Route::get('/search/product', "ProductController@search_with_name");
