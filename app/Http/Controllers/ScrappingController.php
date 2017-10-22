<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client as Goutte;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client;

class ScrappingController extends Controller
{
    public function scrape(){
    	$client = Client::getInstance();
    	$client->getEngine()->setPath('../bin/phantomjs');
    	$doc = new \DOMDocument();
    	$request = $client->getMessageFactory()->createRequest('https://abokifx.com/', 'GET');
    	$response = $client->getMessageFactory()->createResponse();
    	$client->send($request, $response);
    	$doc->loadHTML($response->getContent());
    	$html = $doc->saveHTML();
    	$crawler = new Crawler($html);
    	$presentRates= array();

    	$presentRates['date']= $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td.table-col.datalist')->text();

    	$presentRates['dollars'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(2)')->text();
   		
   		$presentRates['pounds'] = $crawler->filter('body > div.wrapper-home > div.home-section > div > div.lagos-market-rates > div > div.lagos-inner-holder > div.table-grid > table > tbody > tr:nth-child(1) > td:nth-child(3)')->text();

   		echo 'Date: '.$presentRates['date'].'<br/>';
   		echo 'Dollars: '.$presentRates['dollars'].'<br/>';
   		echo 'Pounds: '.$presentRates['pounds'];
	}	
}
