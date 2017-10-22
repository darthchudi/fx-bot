<?php

use App\Services\Twitter\TwitterService;

Route::get('/', function (TwitterService $twitter) {
    $mentions = $twitter->getMentions();
    dd($mentions);
});

Route::get('/scrape', 'ScrappingController@Scrape');