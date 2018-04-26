<?php

use App\Services\Twitter\TwitterService;

Route::get('/', function () {
    // $mentions = $twitter->getMentions();
    // dd($mentions);
    return "asas";
});

// Route::get('/scrape', 'ScrappingController@scrape');
// Route::get('/gif', 'ScrappingController@tweetGif');
// Route::get('/gif_create', 'ScrappingController@getGif');
// Route::get('/heh', 'ScrappingController@heh');

Route::get('/scrape', 'ScrappingController@get_rates');


