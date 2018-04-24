<?php

namespace App\Services\Scrapper;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler as Crawler;
use App\Rate;


class ScrappingService{
  public function getRates(){
	$data = [];
	$html = file_get_contents('https://abokifx.com/');
	$crawler = new Crawler($html);
	$row = $crawler->filterXPath('//table/thead')->nextAll()->filterXPath('//tr');
	$data['date'] = $row->filterXPath('//td')->eq(0)->text();
	$data['dollars'] = $row->filterXPath('//td')->eq(1)->text();
	$data['pounds'] = $row->filterXPath('//td')->eq(2)->text();
	return $data;
  }

  
}