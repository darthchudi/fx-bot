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
use Carbon\Carbon;

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
        $presentHour = Carbon::now("Africa/Lagos")->hour;

        if($presentHour==10 || $presentHour==14 || $presentHour== 20){
            echo '['.Carbon::now("Africa/Lagos")->format('d-m-Y H:i:s').'] Sending Tweet';

            //Call the function to fetch the scrapped data with our Scrapping Service class object
            $data = $scrapper->getRates();

            //Function to determine what time of the day it is
            $period=$period->getPeriod();

            //Apply the filter to clean the data
            $dollars = $filter->removeAsteriks($data['dollars']);
            $pounds = $filter->removeAsteriks($data['pounds']);

            $twitter->tweet($dollars, $pounds, $period);    
        } 
        else{
            echo "Not yet time to tweet!";
        }

    }
}
