<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Jobs\ScrapeRatesJob;

class ScrapeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fxbot:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes Rates from AbokiFX';
    public $test = false;

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
        dispatch(new ScrapeRatesJob());
    }
}
