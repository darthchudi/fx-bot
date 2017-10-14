<?php

namespace App\Console\Commands;

use App\Services\Twitter\Exceptions\RateLimitExceededException;
use App\Jobs\SendTweet;
use App\Tracking;
use Illuminate\Console\Command;
use App\Services\Twitter\TwitterService;
class ReplyToTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fxbot:reply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replies to recent mentions';

    protected $twitter;
    protected $ml;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TwitterService $twitter)
    {
        parent::__construct();
        $this->twitter=$twitter;
        $this->ml = app()->make('monkeylearn');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Tracking $tracking)
    {
        $tracked = $tracking->latestFirst();
        try{
            $mentions = $this->twitter->getMentions($tracked->count() ? $tracked->first()->twitter_id : null);
        } catch(RateLimitExceededException $e){
            return $this->error('Twitter rate limit exceeded');
        }

        if(!$mentions->count()){
            return $this->info('No mentions to process');
        }
        $text = $mentions->map(function($mention){
            return $mention->text ;
        });

        $sentiments = $this->ml->classifiers->classify('cl_qkjxv9Ly', $text->toArray(), false);
        $mentions->each(function($mention, $index) use ($sentiments){
             dispatch(new SendTweet($mention->id, $mention->user->screen_name, $mention->user->name, "I'm alive!"));
        });
       
    } 
}
