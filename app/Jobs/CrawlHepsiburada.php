<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Product;
use \Goutte\Client;

class CrawlHepsiburada implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->product = \App\Product::find($this->id);
        if(!$this->product) return;
        /*$client = new \Goutte\Client();
        $client->setHeader('User-Agent', env('HB_USERAGENT', "FUZM/v1.0r3 Discovery"));*/
        $client = GetGoutteForCrawler();
        $crawler = $client->request('GET', $this->product->productURL);

        try {
            $product_price = $crawler->filter('#offering-price')->attr('content');
            
        } catch (\Exception $e) {
            $product_price = 0;
        }

        try {
            if(true/*$this->product->productid == "" || $this->product->productid == null*/){
                $this->product->productid = $crawler->filter('input[name=productId]')->attr('value');
            }
        } catch (\Exception $e) {
            //throw $th;
        }
        

        $price = new \App\Price;
        $price->price = doubleval($product_price);
        $price->productID = $this->product->id;
        $price->pricedate = now();
        $price->save();
        $this->product->last_receive = now();
        $this->product->save();

        //HepsiburadaDiscoveryService::dispatch($this->product->productid);


    }
}
