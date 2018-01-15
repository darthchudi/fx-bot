<?php

namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Twitter\TwitterService;
use App\Services\Helpers\FilterHelper;
use App\Services\Scrapper\ScrappingService as Scrapper;
use App\Services\Helpers\DateHelper as Period;

class TweetRatesJob implements ShouldQueue
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
    public function handle(TwitterService $twitter, FilterHelper $filter, Scrapper $scrapper, Period $period)
    {
        //Call the function to fetch the scrapped data with our Scrapping Service class object
        $data = $scrapper->getRates();

        //Function to determine what time of the day it is
        $period=$period->getPeriod();

        //Apply the filter to clean the data
        $dollars = $filter->removeAsteriks($data['dollars']);
        $pounds = $filter->removeAsteriks($data['pounds']);

        $twitter->tweet($dollars, $period, 'dollars');
        $twitter->tweet($pounds, $period, 'pounds');
        // $twitter->tweetDollars($dollars, $period);

    }
}
