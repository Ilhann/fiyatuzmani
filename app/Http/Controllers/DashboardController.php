<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index_v1(){
        $product = \App\Product::orderBy('last_receive', 'desc')->limit(1)->first();
        if($product){
            return view('index', ['productId' => $product->id, 'title' => $product->title]);
        }
    }
}
