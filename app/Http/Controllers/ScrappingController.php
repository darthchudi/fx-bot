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

use Giphy;

class ScrappingController extends Controller
{

}
