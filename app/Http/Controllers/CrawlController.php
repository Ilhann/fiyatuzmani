<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class CrawlController extends Controller
{
    //
    public function add_new(Request $request){
        $client = new Client();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 10.1; Win64; x64) AppleWebKit/538.18 (KHTML, like Gecko) Chrome/82.0.4813.110 Safari/538.18");
        $crawler = $client->request('GET', $request->get('url'));
        $price = $crawler->filter('#offering-price')->attr('content');
        $name = $crawler->filter('#product-name')->text();

        if($apps = \App\Product::where('productURL', $request->get('url'))->first()){
            return "Product already exists. Details:\n".strval($apps);
        }
        $product = new \App\Product;
        $product->productURL = $request->get('url');
        $product->provider = "hepsiburada";
        $product->title = $name;
        $product->last_receive = now()->subSeconds(3600);
        $product->source = "url";
        $product->productid = $crawler->filter('input[name=productId]')->attr('value');
        $product->save();

        return "Product with ID: ". strval($product->id) ." successfully added to tracking table. Current Price: " . $price . ", Current Title: " . $name . "";

    }
}
