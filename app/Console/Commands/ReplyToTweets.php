<?php

namespace App\Console\Commands;

use App\Services\Twitter\Exceptions\RateLimitExceededException;
use App\Jobs\SendTweet;
use App\Tracking;
use Illuminate\Console\Command;
use App\Services\Twitter\TwitterService;
/*use App\Common\ResponseHelper;
use App\Common\EmojiTing;*/
/*use App\Services\Helpers\EmojiHelper;*/
use App\Services\Helpers\ResponseHelper;
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
    public function handle(Tracking $tracking, ResponseHelper $response/*, EmojiHelper $emojis*/)
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

        //Extract mentions content
        $text = $mentions->map(function($mention){
            return $mention->text;
        });

        //Process mentions with Monkey learn and generate sentiment 
        $sentiments = $this->ml->classifiers->classify('cl_qkjxv9Ly', $text->toArray(), false);

        //Dispatch mention details and sentiment to job
        $mentions->each(function($mention, $index) use ($sentiments, $response){
            $tweet_id=$mention->id_str;
            $screen_name=$mention->user->screen_name;
            $url = "https://twitter.com/$screen_name/status/$tweet_id";

            $textReply = $response->random($sentiments->result[$index][0]['label']);
            /*$emojiReply = $emoji->random($sentiments->result[$index][0]['label']);*/
            $reply = $textReply /*+ $emojiReply */ . ' ' . $url;

             dispatch(new SendTweet(
                $mention->id,
                $screen_name, 
                $mention->user->name, 
                $reply
                ));
        });
       
    } 
}
