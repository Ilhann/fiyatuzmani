<?php

Route::get('/ffffffffffffffff', function () {
    return view('starter');
});

Route::get('/search', function () {
    return view('search');
});

Route::get('/_price/{id}', ['uses' => "priceDashboard@renderView"]);

Route::get('/ping', function () {
    return "pong";
});

Route::get('/hepsiburada/add_product', "CrawlController@add_new_hepsiburada");
Route::get('/trendyol/add_product', "CrawlController@add_new_trendyol");
Route::get('/price/{id}', ['uses' => "PriceController@get_with_id"])->name('price.get_with_id');;
Route::get('/search/product', "ProductController@search_with_name");
