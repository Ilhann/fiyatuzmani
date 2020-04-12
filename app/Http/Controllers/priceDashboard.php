<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class priceDashboard extends Controller
{
    public function renderView($id){
        $product = \App\Product::find($id);
        if($product){
            return view('price', ['productId' => $product->id, 'title' => $product->title]);
        }
        abort(404);
    }
}
