<?php

use App\Services\Twitter\TwitterService;

Route::get('/', function (TwitterService $twit) {
    dd($twit);
});
