<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ScrapeRatesJob;
use App\Jobs\TweetRatesJob;

class TweetRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fxbot:tweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tweet Rates';

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
        dispatch(new TweetRatesJob());
    }
}
