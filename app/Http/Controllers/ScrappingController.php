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

  public function scrape(){
    $data = [];
    $html = file_get_contents('https://abokifx.com/');
    $crawler = new Crawler($html);
    $row = $crawler->filterXPath('//table/thead')->nextAll()->filterXPath('//tr');
    $data['date'] = $row->filterXPath('//td')->eq(0)->text();
    $data['dollars'] = $row->filterXPath('//td')->eq(1)->text();
    $data['pounds'] = $row->filterXPath('//td')->eq(2)->text();

    dd($data);

  }

  public function tweetGif(){
    $twitter->tweetGif();
  } 
      
}
