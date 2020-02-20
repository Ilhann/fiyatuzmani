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

    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug($this->id);
        $prd = \App\Product::find($this->id);
        Log::debug($prd->id ."has arrived. Lets crawl!");
        $client = new \Goutte\Client();
        Log::debug($prd->productURL);
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 10.1; Win64; x64) AppleWebKit/538.18 (KHTML, like Gecko) Chrome/83.0.4813.110 Safari/538.18");
        $crawler = $client->request('GET', $prd->productURL);
        $product_price = $crawler->filter('#offering-price')->attr('content');
        Log::debug("Price Found: ".$product_price);
        Log::debug($prd->id ." crawl complete. Creating database entry");

        $price = new \App\Price;
        $price->price = doubleval($product_price);
        $price->productID = $prd->id;
        $price->pricedate = now();
        Log::debug($prd->id ." saving to databse...");
        $price->save();
        $prd->last_receive = now();
        $prd->save();
        Log::debug($prd->id ." saved.");


    }
}
