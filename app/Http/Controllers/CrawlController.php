<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class CrawlController extends Controller
{
    //
    public function add_new_hepsiburada(Request $request){
        $client = GetGoutteForCrawler();
        $client->setHeader('User-Agent', $useragent);
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

    public function add_new_trendyol(Request $request){
        $client = GetGoutteForCrawler();
        $client->setHeader('User-Agent', $useragent);
        $crawler = $client->request('GET', $request->get('url'));
        $price = $crawler->filter('meta[name="twitter:data1"]')->attr('content');
        $name = $crawler->filter('.pr-nm')->text();

        if($apps = \App\Product::where('productURL', $request->get('url'))->first()){
            return "Product already exists. Details:\n".strval($apps);
        }
        $product = new \App\Product;
        $product->productURL = $request->get('url');
        $product->provider = "trendyol";
        $product->title = $name;
        $product->last_receive = now()->subSeconds(90000);
        $product->source = "url";
        //$product->productid = $crawler->filter('input[name=productId]')->attr('value');
        $product->save();

        return "[TRENDYOL] Product with ID: ". strval($product->id) ." successfully added to tracking table. Current Price: " . $price . ", Current Title: " . $name . "";

    }
}
