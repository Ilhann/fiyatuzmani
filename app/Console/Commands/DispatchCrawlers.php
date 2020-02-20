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
        $products = \App\Product::where('last_receive', "<", now()->subSeconds(3600))->get();
        foreach ($products as $product) {
            CrawlHepsiburada::dispatch($product->id);
            Log::debug("dispatching ".$product->id);
        }
        //
    }
}
