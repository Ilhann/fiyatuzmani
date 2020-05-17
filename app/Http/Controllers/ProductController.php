<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search_with_name(Request $request){
        $querystring = $request->get('query');
        $search_result = \App\Product::select("id", "title", "productURL", "provider", "last_receive")->where("title", "like", "%{$querystring}%")->limit(500)->get();
        return json_encode($search_result);
    }

    public function latest_10_products(Request $request){
        //TODO: this is not a good idea, caching
        $product_result = \App\Product::orderBy("created_at", "desc")->limit(10)->get();
        return json_encode($product_result);
    }
}
