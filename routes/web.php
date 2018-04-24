<?php

use App\Services\Twitter\TwitterService;

Route::get('/', function () {
    // $mentions = $twitter->getMentions();
    // dd($mentions);
});

Route::get('/scrape', 'ScrappingController@scrape');

Route::get('/gif', 'ScrappingController@tweetGif');

Route::get('/gif_create', 'ScrappingController@getGif');

Route::get('/heh', 'ScrappingController@heh');