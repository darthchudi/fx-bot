<?php

use App\Services\Twitter\TwitterService;

Route::get('/', function (TwitterService $twitter) {
    $mentions = $twitter->getMentions();
    dd($mentions);
});