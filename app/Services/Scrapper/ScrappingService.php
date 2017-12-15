<?php

namespace App\Services\Scrapper;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use JonnyW\PhantomJs\Client;
use App\Rate;
use Giphy;


class ScrappingService{
  public function getCrawler(){

    //Create PhantomJS instance and set path to executable path
    $client = Client::getInstance();
    $client->getEngine()->setPath('bin/phantomjs');

    //Create DOM variable
    $doc = new \DOMDocument();

    //Send PhantomJS request and recieve response
    $request = $client->getMessageFactory()->createRequest('https://abokifx.com/', 'GET');
    $response = $client->getMessageFactory()->createResponse();
    $client->send($request, $response);

    //Load response object to html then save it and Create a new crawler object from it
    libxml_use_internal_errors(true);
    $doc->loadHTML($response->getContent());
    libxml_use_internal_errors(false);
    $html = $doc->saveHTML();
    $crawler = new Crawler($html);
    return $crawler;
  }

  
}