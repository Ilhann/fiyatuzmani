<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CrawlHepsiburada;
use Illuminate\Support\Facades\Log;

class DispatchCrawlers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Dispatcher:crawlers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch Slaves';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = \App\Product::where('last_receive', "<", now()->subSeconds(3612))->where('source', '=', 'url')->get();
        foreach ($products as $product) {
            $product->last_dispatch = now();
            $product->save();
            CrawlHepsiburada::dispatch($product->id)->delay(now()->addSeconds(3));
            Log::debug("dispatching ".$product->id);
        }
        
        $rand_number = random_int(5, 10);
        $products = \App\Product::where('last_receive', "<", now()->subSeconds(43214))->where('source', '=', 'discovery')->orderBy('last_receive', 'asc')->limit($rand_number)->get();
        foreach ($products as $product) {
            $product->last_dispatch = now();
            $product->save();
            CrawlHepsiburada::dispatch($product->id)->delay(now()->addSeconds(3));
            Log::debug("(DISCOVERY PRODUCT)dispatching ".$product->id);
        }
    }
}
