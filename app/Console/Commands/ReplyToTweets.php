<?php

namespace App\Console\Commands;

use App\Services\Twitter\Exceptions\RateLimitExceededException;
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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TwitterService $twitter)
    {
        parent::__construct();
        $this->twitter=$twitter;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $mentions = $this->twitter->getMentions();
        } catch(RateLimitExceededException $e){
            return $this->error('Twitter rate limit exceeded');
        }
        dd($mentions);
    } 
}
