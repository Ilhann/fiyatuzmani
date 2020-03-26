<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search_with_name(Request $request){
        $querystring = $request->get('query');
        $search_result = \App\Product::select("id", "title", "productURL")->where("title", "like", "%{$querystring}%")->get();
        return json_encode($search_result);
    }
}
