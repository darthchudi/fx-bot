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
    public $scrapper;
    public $period;
    public $presentRates= array();
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->period = new Period();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Create a new Scrapper class and store the getCrawler() method in a crawler variable
        $scrapper = new Scrapper();
        $crawler = $scrapper->getCrawler();


        //Pick out nodes containing rates from the retrieved crawler object
        $this->presentRates['date']= $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td.table-col.datalist')->text();

        $this->presentRates['dollars'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(2)')->text();
        
        $this->presentRates['pounds'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(3)')->text();

        $this->presentRates['euros']=$crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(4)')->text();

        $this->period=$this->period->getPeriod();


        //Store current rates into database
        Rate::create([
          'rates_date'=>$this->presentRates['date'],
          'dollars'=>$this->presentRates['dollars'],
          'pounds'=>$this->presentRates['pounds'],
          'euros'=>$this->presentRates['euros'],
          'time_period'=>$this->period
        ]);

        echo "Dollars: {$this->presentRates['dollars']}, Pounds: {$this->presentRates['pounds']}, 
            Euros: {$this->presentRates['euros']}, Time Period: {$this->period}";
    }

}
