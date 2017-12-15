<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Twitter\TwitterService;

use App\Rate;

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
    public function handle(TwitterService $twitter, Rate $rate)
    {
        //Get Latest Rate
        $currentRate = $rate->latestFirst()->first();
        $dollars = $currentRate->dollars;
        $dollars = substr($dollars, 6, 3);
        $pounds = substr($currentRate->pounds, 6, 3);
        $period = $currentRate->time_period;


        $twitter->tweetGif();
    }
}
