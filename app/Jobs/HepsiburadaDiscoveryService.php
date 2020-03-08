<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HepsiburadaDiscoveryService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        //
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $prd_counts = \App\Product::where("source", "=", "discovery")->count();
        if($prd_counts > 10000) return; // 10000 discovery product limit reached, do not add any products via crawler service

        $client = new Client();
        $response = $client->request("GET", "https://recommendation.hepsiburada.com/api/v1/recommendations/withproductinfo?placements=item_page.web-rank1&productId=". $this->id);

        if(!$response->getStatusCode() == 200)
            return;

        $output = json_decode($response->getBody());
        if(empty($output->data->placements)){
            Log::debug("Discovery is empty for: ".$this->id);
            return;
        }
        Log::debug("Discovery proceed for: ".$this->id);
        
        $products = $output->data->placements[0]->products;
        if(!$product_count = count($products)){
            Log::debug("No suggestions from Hepsiburada for: ".$this->id);
            return;
        }
        $product = $products[random_int(0, $product_count - 1)];

        if($apps = \App\Product::where('productURL', $product->productUrl)->first()){
            return;
        }

        Log::debug("Discovery service discovered: ".$product->productUrl);
            
        $goutte_client = new \Goutte\Client();
        $goutte_client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 10.1; Win64; x64) AppleWebKit/538.18 (KHTML, like Gecko) Chrome/82.0.4813.110 Safari/538.18");
        $crawler = $goutte_client->request('GET', $product->productUrl);
        $name = $crawler->filter('#product-name')->text();

        $_product = new \App\Product;
        $_product->productURL = $product->productUrl;
        $_product->provider = "hepsiburada";
        $_product->title = $name;
        $_product->last_receive = now()->subSeconds(36200);
        $_product->last_dispatch = now()->subSeconds(186400);
        $_product->source = "discovery";
        $_product->productid = $crawler->filter('input[name=productId]')->attr('value');
        $_product->save();
        return;// code on the bottom is for all (or first) products. For now get only random one.
        /*foreach ($products as $product){
            Log::debug("Discovery service discovered: ".$product->productUrl);
            
            $goutte_client = new \Goutte\Client();
            $goutte_client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 10.1; Win64; x64) AppleWebKit/538.18 (KHTML, like Gecko) Chrome/82.0.4813.110 Safari/538.18");
            $crawler = $goutte_client->request('GET', $product->productUrl);
            $name = $crawler->filter('#product-name')->text();

            if($apps = \App\Product::where('productURL', $product->productUrl)->first()){
                return;
            }

            $_product = new \App\Product;
            $_product->productURL = $product->productUrl;
            $_product->provider = "hepsiburada";
            $_product->title = $name;
            $_product->last_receive = now();
            $_product->source = "discovery";
            $_product->productid = $crawler->filter('input[name=productId]')->attr('value');
            $_product->save();
            break; // temporarily, for prevent outbreaking number of products

        }*/
    }
}
