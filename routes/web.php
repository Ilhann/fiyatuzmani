<?php


Route::get('/search', function () {
    return view('search');
});

Route::get('/_price/{id}', ['uses' => "priceDashboard@renderView"])->name('price.dashboard_with_id');
Route::get('/', ['uses' => "DashboardController@index_v1"]);

Route::get('/ping', function () {
    return "pong";
});

Route::get('/hepsiburada/add_product', "CrawlController@add_new_hepsiburada");
Route::get('/trendyol/add_product', "CrawlController@add_new_trendyol");
Route::get('/price/{id}', ['uses' => "PriceController@get_with_id"])->name('price.get_with_id');
Route::get('/search/product', "ProductController@search_with_name");
Route::get('/product/latest_10', "ProductController@latest_10_products")->name('product.latest_10');
