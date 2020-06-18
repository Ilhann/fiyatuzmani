<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

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
    
    public function add(Request $request){
        $result = array();
        $result["success"] = false;

        $url = strval($request->input("productURL"));

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            $result["message"] = "URL Geçerli Değil.";
            return json_encode($result);
        }

        //?$url = explode("?", $url);

       $check_hb = strpos($url, "hepsiburada.com");
       $check_ty = strpos($url, "trendyol.com");
       $mode = null;
       if($check_hb !== false){
            $url = explode("?", $url)[0];
            $client = GetGoutteForCrawler();
            $crawler = $client->request('GET', $url);
            $price = $crawler->filter('#offering-price')->attr('content');
            $name = $crawler->filter('#product-name')->text();

            if($apps = \App\Product::where('productURL', $url)->first()){
                $result["message"] = "HB Ürün zaten var. ID: ".$apps->id." Eklenme tarihi: " .$apps->created_at;
                return json_encode($result);
            }
            $product = new \App\Product;
            $product->productURL = $url;
            $product->provider = "hepsiburada";
            $product->title = $name;
            $product->last_receive = now()->subSeconds(90000);
            $product->last_dispatch = now()->subSeconds(90000);
            $product->source = "url";
            $product->productid = $crawler->filter('input[name=productId]')->attr('value');
            $product->save();

            $result["success"] = true;
            $result["message"] = "***HEPSIBURADA*** Ürün başarıyla eklendi. ID: ". strval($product->id) .". Mevcut fiyat: " . $price . ", Mevcut başlık: " . $name . "";

       } else if($check_ty !== false){
            $url = explode("?", $url)[0];
            $client = GetGoutteForCrawler();
            $crawler = $client->request('GET', $url);
            $price = $crawler->filter('meta[name="twitter:data1"]')->attr('content');
            $name = $crawler->filter('.pr-nm')->text();

            if($apps = \App\Product::where('productURL', $url)->first()){
                $result["message"] = "Trendyol Ürün zaten var. ID: ".$apps->id." Eklenme tarihi: " .$apps->created_at;
                return json_encode($result);
            }
            $product = new \App\Product;
            $product->productURL = $url;
            $product->provider = "trendyol";
            $product->title = $name;
            $product->last_receive = now()->subSeconds(90000);
            $product->last_dispatch = now()->subSeconds(90000);
            $product->source = "url";
            $product->save();
            $result["success"] = true;
            $result["message"] = "***TRENDYOL*** Ürün başarıyla eklendi. ID: ". strval($product->id) .". Mevcut fiyat: " . $price . ", Mevcut başlık: " . $name . "";

       } else {
            $result["message"] = "Geçersiz sağlayıcı";
            return json_encode($result);
       }

       return json_encode($result);
    }
}
