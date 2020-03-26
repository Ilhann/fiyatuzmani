<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function get_with_id($id){
        $search_result = \App\Price::select("pricedate", "price")->where("productID", "{$id}")->orderBy("pricedate", "asc")->get();
        return json_encode($search_result);
    }
}
