<?php

namespace App\Http\Controllers;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;
use Goutte\Client as Goutte;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as Client;
use App\Rate;
use App\Services\Twitter\TwitterService;

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

  public function tweetGif(){
    $twitter->tweetGif();
    // $string = "361 / 375*";
    // dd(substr($string, 6, 3));
  }


  public function scrape(){
  	$client = Client::getInstance();
  	$client->getEngine()->setPath('../bin/phantomjs');
  	$doc = new \DOMDocument();
  	$request = $client->getMessageFactory()->createRequest('https://abokifx.com/', 'GET');
  	$response = $client->getMessageFactory()->createResponse();
  	$client->send($request, $response);
    libxml_use_internal_errors(true);
  	$doc->loadHTML($response->getContent());
    libxml_use_internal_errors(false);
  	$html = $doc->saveHTML();
  	$crawler = new Crawler($html);

  	$this->presentRates['date']= $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td.table-col.datalist')->text();

  	$this->presentRates['dollars'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(2)')->text();
 		
 		$this->presentRates['pounds'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(3)')->text();

    $this->presentRates['euros']=$crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(4)')->text();

 		echo 'Date: '.$this->presentRates['date'].'<br/>';
 		echo 'Dollars: '.$this->presentRates['dollars'].'<br/>';
 		echo 'Pounds: '.$this->presentRates['pounds'];
    echo 'Euros: '.$this->presentRates['euros'];
    $this->save();
  } 

  public function save(){
    $exists = Rate::where('date', $this->presentRates['date']);
    if($exists){
      return 'Record exists already';
    }
    else{
      Rate::create([
        'rates_date'=>$this->presentRates['date'],
        'dollars'=>$this->presentRates['dollars'],
        'pounds'=>$this->presentRates['pounds'],
        'euros'=>$this->presentRates['euros']
      ]);
    }
  }
      
}
