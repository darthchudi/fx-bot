<?php

namespace App\Jobs;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use JonnyW\PhantomJs\Client;
use App\Rate;
use App\Services\Scrapper\ScrappingService as Scrapper;
use App\Services\Helpers\DateHelper as Period;

class ScrapeRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle(Scrapper $scrapper, Period $period)
    {
        //Call the function to fetch the scrapped data with our Scrapping Service class object
        $data = $scrapper->getRates();

        $period=$period->getPeriod();


        //Store current rates into database
        Rate::create([
          'rates_date'=>$data['date'],
          'dollars'=>$data['dollars'],
          'pounds'=>$data['pounds'],
          'time_period'=>$period
        ]);

        echo "Dollars: {$data['dollars']}, Pounds: {$data['pounds']}, Time Period: {$period}";
    }

}
