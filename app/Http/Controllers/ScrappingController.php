<?php

namespace App\Http\Controllers;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;
use Goutte\Client as Goutte;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as Client;
use App\Services\Twitter\TwitterService;
use App\Services\Helpers\DateHelper as Period;
use App\Services\Helpers\FilterHelper;
use App\Rates;

use Giphy;

class ScrappingController extends Controller
{

  public $presentRates= array();

  public function getGif(TwitterService $twitter){
    $giphy = Giphy::search('freaking out');
    $count=22;

    foreach($giphy->data as $gifs){
      $url = $gifs->images->original->url;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $curlData = curl_exec ($ch);
      curl_close ($ch);
      $downloadPath = __DIR__."/../../../gifs/screaming_".$count.".gif";
      file_put_contents($downloadPath, $curlData);
      $count+=1;
    }
  }

  public function scrape(TwitterService $twitter, Period $period, FilterHelper $filter){
    $data = [];
    $html = file_get_contents('https://abokifx.com/');
    $crawler = new Crawler($html);
    $row = $crawler->filterXPath('//table/thead')->nextAll()->filterXPath('//tr');
    $data['date'] = $row->filterXPath('//td')->eq(0)->text();
    $data['dollars'] = $row->filterXPath('//td')->eq(1)->text();
    $data['pounds'] = $row->filterXPath('//td')->eq(2)->text();

    $period = $period->getPeriod();
    $dollars = $filter->removeAsteriks($data['dollars']);

    $twitter->tweetDollars($dollars, $period);

  }  


  public function heh(FilterHelper $filter){
    $data = '365 / 365';
    $filter->removeAsteriks($data);
  }  

  public function get_rates(){
    $data = [];
    $html = file_get_contents('https://abokifx.com/');
    $crawler = new Crawler($html);
    $row = $crawler->filterXPath('//table/thead')->nextAll()->filterXPath('//tr');
    $data['date'] = $row->filterXPath('//td')->eq(0)->text();
    $data['dollars'] = $row->filterXPath('//td')->eq(1)->text();
    $data['pounds'] = $row->filterXPath('//td')->eq(2)->text();
    // $data = json_encode($data);
    $date = $data['date'];

    $dollars = $data['dollars'];
    $pounds = $data['pounds'];

    $dollars = explode('/',$dollars);
    $pounds = explode('/',$pounds);

    $dollars_buy = $dollars[0];
    $pounds_buy = $pounds[0];

    $dollars_sell = $dollars[1];
    $pounds_sell = $pounds[1];

    $this->insert($date,$dollars_buy,$dollars_sell,$pounds_buy,$pounds_sell);
  }

  public function insert($date,$dollar_buy,$dollar_sell,$pounds_buy,$pounds_sell){
      $rate = new Rates();
      
      $check_rate = $rate->where('Date',date("Y-m-d"))->first();
      if($check_rate){
        $this->update_rate($check_rate);
      }else{
        $rate->Date =  $date;
        $rate->Dollar_BUY = $dollar_buy;
        $rate->Dollar_SELL = $dollar_sell;
        $rate->Pounds_BUY = $pounds_buy;
        $rate->Pounds_SELL =  $pounds_sell;
        $rate->save();  
      }
      
  }

  public function cron(){
    $rate = new Rate();
    $check_rate = $rate->get();
  }

  public function update_rate($data_rate){
    $rate = new Rates();
    $time = str_replace("pm", "", date("h:i:sa"));
    $timestamp = $data_rate->Date ." ".$time;
    dd($timestamp);  
    $check_rate = $rate->where('Date',date("Y-m-d"))->first();


  }

}
