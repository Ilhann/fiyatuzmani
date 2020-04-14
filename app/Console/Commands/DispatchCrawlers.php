<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CrawlHepsiburada;
use App\Jobs\CrawlTrendyol;
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
        $products = \App\Product::where('last_receive', "<", now()->subSeconds(43000))->where('last_dispatch', "<", now()->subSeconds(7000))->where('source', '=', 'url')->where('provider', '=', 'trendyol')->orderBy('last_receive', 'asc')->limit(7)->get();
        $trendyol_dispatch_delay = 1;
        foreach ($products as $product) {
            $product->last_dispatch = now();
            $product->save();
            $trendyol_dispatch_delay += 6;
            CrawlTrendyol::dispatch($product)->delay(now()->addSeconds($trendyol_dispatch_delay));
            //Log::debug("TRENDYOL dispatching ".$product->id);
        }
        $products = \App\Product::where('last_receive', "<", now()->subSeconds(43000))->where('last_dispatch', "<", now()->subSeconds(7000))->where('source', '=', 'url')->where('provider', '=', 'hepsiburada')->orderBy('last_receive', 'asc')->limit(2)->get();
        $hb_dispatch_delay = 5;
        foreach ($products as $product) {
            $product->last_dispatch = now();
            $product->save();
            $hb_dispatch_delay += 20;
            CrawlHepsiburada::dispatch($product)->delay(now()->addSeconds($hb_dispatch_delay));
        }
    }
}
